<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSecteurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'code' => 'required|string|unique:secteurs|max:10',
            'description' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du secteur est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'code.required' => 'Le code est obligatoire.',
            'code.string' => 'Le code doit être une chaîne de caractères.',
            'code.unique' => 'Ce code est déjà utilisé.',
            'code.max' => 'Le code ne doit pas dépasser 10 caractères.',

            'description.string' => 'La description doit être une chaîne de caractères.',
        ];
    }
}
