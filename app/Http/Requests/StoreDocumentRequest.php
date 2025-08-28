<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Document::class);
    }

    public function rules(): array
    {
        return [
            'documents' => 'required|array|max:10',
            'documents.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'documents.required' => 'Au moins un document est requis.',
            'documents.array' => 'Les documents doivent être envoyés sous forme de tableau.',
            'documents.max' => 'Vous ne pouvez pas télécharger plus de 10 documents à la fois.',
            'documents.*.required' => 'Chaque fichier est requis.',
            'documents.*.file' => 'Chaque élément doit être un fichier valide.',
            'documents.*.mimes' => 'Formats autorisés : PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, TXT.',
            'documents.*.max' => 'Chaque fichier ne peut pas dépasser 10 MB.',
        ];
    }
}