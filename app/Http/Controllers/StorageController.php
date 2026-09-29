<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function show($file)
    {
        return response()->make(Storage::get($file), 200, [
            'Content-Type' => Storage::mimeType($file),
            'Content-Disposition' => 'inline; filename="' . $file . '"'
        ]);
    }
}
