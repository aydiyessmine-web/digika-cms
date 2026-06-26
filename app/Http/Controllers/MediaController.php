<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\SettingsHelper;

class MediaController extends Controller
{
    public function index()
{
    $media = Media::with('category')->get();
    $mediaData = $media->map(function($item) {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'type' => $item->type,
            'file_path' => $item->file_path,
            'url' => $item->url,
            'size' => $item->size,
            'category_id' => $item->category_id,
            'category' => $item->category ? [
                'id' => $item->category->id,
                'name' => $item->category->name
            ] : null,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ];
    });
    
    $categories = Category::all();
    
    $totalMedia = Media::count();
    $imagesCount = Media::where('type', 'image')->count();
    $videosCount = Media::where('type', 'video')->count();
    $pdfCount = Media::where('type', 'pdf')->count();
    $documentsCount = Media::where('type', 'document')->count();
    $webpagesCount = Media::where('type', 'webpage')->count();
    
    // ✅ Calcul plus robuste
    $mediaWithSize = Media::where('type', '!=', 'webpage')->get();
    $totalSize = $mediaWithSize->sum('size');
    
    // Convertir en MB avec 1 décimale
    $totalSizeMB = $totalSize > 0 ? number_format($totalSize / 1048576, 1) : 0;
    
    // Debug (à supprimer après)
    // \Log::info('Total size (bytes): ' . $totalSize);
    // \Log::info('Total size (MB): ' . $totalSizeMB);
    
    $mediaPerPage = SettingsHelper::getMediaPerPage();
    
    return view('media.index', compact(
        'mediaData', 'categories', 'totalMedia', 'imagesCount', 'videosCount',
        'pdfCount', 'documentsCount', 'webpagesCount', 'totalSizeMB', 'mediaPerPage'
    ));
}
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:image,video,pdf,document,webpage',
            'category_id' => 'nullable|exists:categories,id',
            'url' => 'nullable|url|max:2048',
            'file' => 'nullable|file|max:102400',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation échouée', 'errors' => $validator->errors()], 422);
        }
        
        $data = [
            'title' => $request->title,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ];
        
        // إذا كان نوع webpage، نستعمل الـ URL
        if ($request->type === 'webpage') {
            if (!$request->url) {
                return response()->json(['message' => 'Le champ URL est requis pour une page web'], 422);
            }
            $data['url'] = $request->url;
            $data['file_path'] = null;
            $data['size'] = 0;
            $data['duration'] = null;
            $data['resolution'] = null;
        } else {
            // للأنواع الأخرى، نستعمل الـ file
            if (!$request->hasFile('file')) {
                return response()->json(['message' => 'Un fichier est requis'], 422);
            }
            
            $file = $request->file('file');
            if (!$file->isValid()) {
                return response()->json(['message' => 'Le fichier est invalide'], 422);
            }
            
            $originalExtension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $originalExtension;
            $path = $file->storeAs('media', $filename, 'public');
            
            $data['file_path'] = $path;
            $data['size'] = $file->getSize();
            $data['duration'] = $request->duration;
            $data['resolution'] = $request->resolution;
            $data['url'] = null;
        }
        
        $media = Media::create($data);
        
        return response()->json(['success' => true, 'media' => $media], 201);
    }
    
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
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
            'url' => 'nullable|url|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $media = Media::findOrFail($id);
        $media->update($request->only(['title', 'category_id', 'resolution', 'url']));
        
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
        
        if ($media->type === 'webpage' && $media->url) {
            return response()->json([
                'content' => $media->url,
                'type' => 'webpage',
                'filename' => $media->title,
                'is_webpage' => true,
                'url' => $media->url
            ]);
        }
        
        if (!$media->file_path) {
            return response()->json([
                'content' => 'Aucun fichier associé à ce média.',
                'error' => true
            ]);
        }
        
        $fullPath = Storage::disk('public')->path($media->file_path);
        
        if (!file_exists($fullPath)) {
            return response()->json([
                'content' => 'Fichier introuvable sur le serveur.',
                'error' => true
            ]);
        }
        
        $extension = strtolower(pathinfo($media->file_path, PATHINFO_EXTENSION));
        $content = '';
        
        if ($extension === 'pdf') {
            $txtFile = tempnam(sys_get_temp_dir(), 'pdf_') . '.txt';
            $command = 'pdftotext -layout "' . $fullPath . '" "' . $txtFile . '" 2>&1';
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($txtFile)) {
                $content = file_get_contents($txtFile);
                unlink($txtFile);
            } else {
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
        } elseif ($extension === 'txt') {
            $content = file_get_contents($fullPath);
            if (empty($content)) {
                $content = "Fichier texte vide.";
            }
        } elseif ($extension === 'docx') {
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
        } else {
            $content = "Aperçu non disponible pour ce type de fichier (.$extension).";
        }
        
        return response()->json([
            'content' => $content,
            'type' => $extension,
            'filename' => $media->title,
            'size' => round($media->size / 1024, 2),
            'is_webpage' => false
        ]);
    }
}