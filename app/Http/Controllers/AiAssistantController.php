<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function index()
    {
        return view('ai.index');
    }
}