<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|mimes:jpg,jpeg,png,mp4,avi|max:20480'
        ]);

        $file = $request->file('file');

        $path = $file->store('media', 'public');

        $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

        Media::create([
            'title' => $request->title,
            'type' => $type,
            'file_path' => $path,
            'user_id' => 1 
        ]);

        return back()->with('success', 'Media uploaded successfully');
    }

    public function index()
    {
        $media = Media::latest()->get();
        return view('media.index', compact('media'));
    }
}