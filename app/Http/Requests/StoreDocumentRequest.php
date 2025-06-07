<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_document' => 'required|string',
            'document' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'type_document.required' => 'Le type de document est obligatoire.',
            'type_document.string' => 'Le type de document doit être une chaîne de caractères.',

            'document.required' => 'Le fichier du document est obligatoire.',
            'document.file' => 'Le document doit être un fichier valide.',
            'document.mimes' => 'Le document doit être un fichier de type : pdf, jpg, jpeg ou png.',
            'document.max' => 'Le document ne doit pas dépasser 5 Mo.',
        ];
    }
}

