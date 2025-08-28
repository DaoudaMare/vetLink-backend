<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $documents = Auth::user()->documents()
            ->with(['user', 'organization'])
            ->latest()
            ->paginate($perPage);
            
        return response()->json([
            'message' => 'Documents récupérés avec succès',
            'data' => DocumentResource::collection($documents)
        ]);
    }

    public function store(StoreDocumentRequest $request)
    {
        $uploadedDocuments = [];
        $errors = [];

        DB::beginTransaction();
        
        try {
            foreach ($request->file('documents') as $index => $file) {
                try {
                    // Déterminer l'organisation si l'utilisateur en a une
                    $organizationId = Auth::user()->organization_id;
                    
                    $path = $file->store('documents/user_' . Auth::id(), 'private');

                    $document = Document::create([
                        'user_id' => Auth::id(),
                        'organization_id' => $organizationId,
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                    ]);

                    $uploadedDocuments[] = $document;
                } catch (\Exception $e) {
                    $errors[] = "Erreur lors de l'upload du fichier {$index}: " . $file->getClientOriginalName();
                }
            }
            
            if (empty($uploadedDocuments)) {
                throw new \Exception('Aucun document n\'a pu être téléchargé');
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'Documents téléchargés avec succès',
                'data' => DocumentResource::collection(collect($uploadedDocuments)),
                'errors' => $errors
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Nettoyer les fichiers uploadés en cas d'erreur
            foreach ($uploadedDocuments as $document) {
                Storage::disk('private')->delete($document->path);
            }
            
            return response()->json([
                'message' => 'Erreur lors du téléchargement des documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        try {
            if (!Storage::disk('private')->exists($document->path)) {
                return response()->json([
                    'message' => 'Fichier non trouvé'
                ], 404);
            }
            
            return Storage::disk('private')->download($document->path, $document->name);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du téléchargement du fichier',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        try {
            // Supprimer le fichier physique
            if (Storage::disk('private')->exists($document->path)) {
                Storage::disk('private')->delete($document->path);
            }
            
            // Supprimer l'enregistrement
            $document->delete();

            return response()->json([
                'message' => 'Document supprimé avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du document',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}