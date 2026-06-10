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
        $query = Media::with('category');
        
        // Filtre par catégorie (recherche par nom de catégorie via relation)
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
        
        $media = $query->paginate(10);
        $categories = Category::all();
        
        // Statistiques
        $totalMedia = Media::count();
        $imagesCount = Media::where('type', 'image')->count();
        $videosCount = Media::where('type', 'video')->count();
        $pdfCount = Media::where('type', 'pdf')->count();
        $documentsCount = Media::where('type', 'document')->count();
        $totalSize = round(Media::sum('size') / 1024 / 1024, 1);
        
        return view('media.index', compact(
            'media', 'categories', 'totalMedia', 'imagesCount', 
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
        $originalMimeType = $file->getMimeType();
        $fileSize = $file->getSize();
        
        // Détection du type réel
        $detectedType = $this->detectFileType($originalMimeType, $originalExtension);
        
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
    
    private function detectFileType($mimeType, $extension)
    {
        $extension = strtolower($extension);
        $mimeType = strtolower($mimeType);
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
            return 'image';
        }
        if (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'])) {
            return 'video';
        }
        if ($extension === 'pdf') {
            return 'pdf';
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
}