<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\ProfileProgress;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{public function store(StoreDocumentRequest $request)
{
    $user = auth()->user();
    $path = $request->file('document')->store('documents', 'public');

    $doc = Document::updateOrCreate(
        [
            'user_id' => $user->id,
            'type_document' => $request->type_document
        ],
        [
            'file_path' => $path,
            'status' => 'pending'
        ]
    );

    // Mise à jour de la progression du profil
    ProfileProgress::updateProgress($user);

    return response()->json([
        'message' => 'Document soumis avec succès',
        'data' => $doc
    ]);
}
}
