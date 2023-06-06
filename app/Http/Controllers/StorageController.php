<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function show($file)
    {
        return response()->make(Storage::disk('s3')->get($file), 200, [
            'Content-Type' => Storage::disk('s3')->mimeType($file),
            'Content-Disposition' => 'inline; filename="' . $file . '"'
        ]);
    }
}
