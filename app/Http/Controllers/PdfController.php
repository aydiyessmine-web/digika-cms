<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    // هذا يعرض صفحة الواجهة (Blade)
    public function show($filename)
    {
        return view('index', ['filename' => $filename]);
    }

    // هذا يرجع محتوى ملف PDF للمتصفح (لا تنساه)
    public function getPdf($filename)
    {
        // افترض أن الملف موجود في مجلد public/uploads
        $path = public_path('uploads/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'الملف غير موجود');
        }

        return response()->file($path);
    }
}