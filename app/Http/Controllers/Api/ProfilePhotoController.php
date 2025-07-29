<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
{
    $validated = $request->validate([
        'photo' => [
            'required',
            'image',
            'mimes:jpeg,png,jpg,webp',
            'max:2048',
            'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
        ]
    ]);

    try {
        $user = $request->user();

        // Utilise la méthode du modèle
        $path = $user->uploadProfilePhoto($validated['photo']);

        return response()->json([
            'photo_url' => $user->photo_url,
            'path' => $path, // Optionnel pour le debug
            'message' => 'Photo de profil mise à jour avec succès'
        ]);

    } catch (\Exception $e) {
        Log::error('Erreur upload photo - User:' . $request->user()->id, [
            'error' => $e->getMessage(),
            'file' => $request->file('photo')?->getClientOriginalName()
        ]);

        return response()->json([
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Échec de la mise à jour de la photo',
            'details' => config('app.debug') ? [
                'trace' => $e->getTraceAsString()
            ] : null
        ], 500);
    }
}
}
