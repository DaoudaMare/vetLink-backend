<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStartupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type_innovation' => 'required|string|max:255',
            'investisseurs_partenaires' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'type_innovation.required' => 'Le type d\'innovation est requis',
            'type_innovation.max' => 'La description ne doit pas dépasser 255 caractères',
            'investisseurs_partenaires.array' => 'Le format des investisseurs est invalide',
        ];
    }
}
