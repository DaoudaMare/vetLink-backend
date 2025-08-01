<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $documents = Auth::user()->documents()->paginate($perPage);
        return response()->json($documents);
    }

    public function store(StoreDocumentRequest $request)
    {
        

        $uploadedDocuments = [];

        foreach ($request->file('documents') as $file) {
            $path = $file->store('documents/user_' . Auth::id(), 'private');

            $document = Document::create([
                'user_id' => Auth::id(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
            ]);

            $uploadedDocuments[] = $document;
        }

        return response()->json($uploadedDocuments, 201);
    }

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        return Storage::disk('private')->download($document->path, $document->name);
    }

    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        Storage::disk('private')->delete($document->path);
        $document->delete();

        return response()->json(null, 204);
    }
}