<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        // جلب كل الوسائط (بدون pagination من السيرفر)
        $query = Media::with('category');
        
        // Filtre par catégorie
        if ($request->filled('category') && $request->category != 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }
        
        // Filtre par type
        if ($request->filled('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Tri
        $sort = $request->get('sort', 'recent');
        if ($sort == 'recent') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort == 'name') {
            $query->orderBy('title', 'asc');
        }
        
        // جلب جميع الوسائط (لـ client-side pagination)
        $allMedia = $query->get();
        
        // إعداد بيانات الوسائط مع معلومات الفئة
        $mediaData = [];
        foreach ($allMedia as $item) {
            $mediaData[] = [
                'id' => $item->id,
                'title' => $item->title,
                'type' => $item->type,
                'file_path' => $item->file_path,
                'size' => $item->size,
                'resolution' => $item->resolution,
                'duration' => $item->duration,
                'category_id' => $item->category_id,
                'category' => $item->category ? ['name' => $item->category->name] : null,
                'created_at' => $item->created_at,
            ];
        }
        
        $categories = Category::all();
        
        // Statistiques (تظل كما هي)
        $totalMedia = Media::count();
        $imagesCount = Media::where('type', 'image')->count();
        $videosCount = Media::where('type', 'video')->count();
        $pdfCount = Media::where('type', 'pdf')->count();
        $documentsCount = Media::where('type', 'document')->count();
        $totalSize = round(Media::sum('size') / 1024 / 1024, 1);
        
        return view('media.index', compact(
            'mediaData', 'categories', 'totalMedia', 'imagesCount', 
            'videosCount', 'pdfCount', 'documentsCount', 'totalSize'
        ));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:102400',
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'resolution' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'duration' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation échouée', 'errors' => $validator->errors()], 422);
        }
        
        $file = $request->file('file');
        
        if (!$file || !$file->isValid()) {
            return response()->json(['message' => 'Le fichier est invalide'], 422);
        }
        
        $originalExtension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        
        // Détection du type réel
        $detectedType = $this->detectFileType($originalExtension);
        
        // Générer nom unique
        $filename = time() . '_' . uniqid() . '.' . $originalExtension;
        $path = $file->storeAs('media', $filename, 'public');
        
        $duration = $request->duration;
        
        $media = Media::create([
            'title' => $request->title,
            'type' => $detectedType,
            'file_path' => $path,
            'size' => $fileSize,
            'resolution' => $request->resolution,
            'category_id' => $request->category_id,
            'duration' => $duration,
            'user_id' => Auth::id(),
        ]);
        
        return response()->json(['success' => true, 'media' => $media], 201);
    }
    
    private function detectFileType($extension)
    {
        $extension = strtolower($extension);
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'])) {
            return 'image';
        }
        if (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'])) {
            return 'video';
        }
        if ($extension === 'pdf') {
            return 'pdf';
        }
        if (in_array($extension, ['doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'])) {
            return 'document';
        }
        return 'document';
    }
    
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }
        $media->delete();
        return response()->json(['success' => true]);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'resolution' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $media = Media::findOrFail($id);
        $media->update($request->only(['title', 'category_id', 'resolution']));
        
        return response()->json(['success' => true]);
    }
    
    public function show($id)
    {
        $media = Media::with('category')->findOrFail($id);
        return response()->json($media);
    }
    public function getContent($id)
    {
        $media = Media::findOrFail($id);
        
        $fullPath = Storage::disk('public')->path($media->file_path);
        
        if (!file_exists($fullPath)) {
            return response()->json([
                'content' => 'Fichier introuvable sur le serveur.',
                'error' => true
            ]);
        }
        
        $extension = strtolower(pathinfo($media->file_path, PATHINFO_EXTENSION));
        $content = '';
        
        // ========== PDF ==========
        if ($extension === 'pdf') {
            // Tentative d'extraction avec pdftotext (si installé sur le serveur)
            $txtFile = tempnam(sys_get_temp_dir(), 'pdf_') . '.txt';
            $command = 'pdftotext -layout "' . $fullPath . '" "' . $txtFile . '" 2>&1';
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($txtFile)) {
                $content = file_get_contents($txtFile);
                unlink($txtFile);
            } else {
                // Fallback: lire le PDF avec une bibliothèque PHP
                if (class_exists('\Spatie\PdfToText\Pdf')) {
                    try {
                        $content = \Spatie\PdfToText\Pdf::getText($fullPath);
                    } catch (\Exception $e) {
                        $content = "Impossible d'extraire le texte de ce PDF.";
                    }
                } else {
                    $content = "Aperçu texte non disponible. Veuillez télécharger le fichier.";
                }
            }
        }
        // ========== TXT ==========
        elseif ($extension === 'txt') {
            $content = file_get_contents($fullPath);
            if (empty($content)) {
                $content = "Fichier texte vide.";
            }
        }
        // ========== DOCX ==========
        elseif ($extension === 'docx') {
            if (class_exists('\PhpOffice\PhpWord\IOFactory')) {
                try {
                    $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
                    $content = '';
                    foreach ($phpWord->getSections() as $section) {
                        foreach ($section->getElements() as $element) {
                            if (method_exists($element, 'getText')) {
                                $content .= $element->getText() . "\n";
                            }
                        }
                    }
                    if (empty($content)) {
                        $content = "Impossible d'extraire le texte de ce document Word.";
                    }
                } catch (\Exception $e) {
                    $content = "Erreur lors de la lecture du document Word: " . $e->getMessage();
                }
            } else {
                $content = "Aperçu non disponible. Installez phpoffice/phpword pour extraire le texte.";
            }
        }
        else {
            $content = "Aperçu non disponible pour ce type de fichier (.$extension).";
        }
        
        return response()->json([
            'content' => $content,
            'type' => $extension,
            'filename' => $media->title,
            'size' => round($media->size / 1024, 2)
        ]);
    }
    /**
     * Get file type based on extension
     */
    private function getFileType($extension)
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $videoTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv'];
        $documentTypes = ['pdf', 'doc', 'docx', 'txt', 'xls', 'xlsx', 'ppt', 'pptx'];
        
        if (in_array($extension, $imageTypes)) {
            return 'image';
        } elseif (in_array($extension, $videoTypes)) {
            return 'video';
        } elseif (in_array($extension, $documentTypes)) {
            return 'document';
        } else {
            return 'other';
        }
    }
}