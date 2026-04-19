<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048', // Reduzi para 2MB para performance
                'dimensions:max_width=4000,max_height=4000',
            ],
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $file = $request->file('file');

            // Gerar nome único e imprevisível (UUID)
            $extension = $file->getClientOriginalExtension();
            $filename = (string) Str::uuid().'.'.$extension;

            // Armazenar no disco público
            $path = $file->storeAs('uploads', $filename, 'public');
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'status' => 'success',
                'url' => $url,
                'path' => $path,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Nenhum arquivo enviado.'], 400);
    }
}
