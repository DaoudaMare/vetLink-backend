<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActiviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'exemples' => 'nullable|string',
            'sous_secteur_id' => 'required|exists:sous_secteurs,id'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'activité est obligatoire.',
            'nom.string' => 'Le nom de l\'activité doit être une chaîne de caractères.',
            'nom.max' => 'Le nom de l\'activité ne doit pas dépasser 255 caractères.',

            'exemples.string' => 'Les exemples doivent être une chaîne de caractères.',

            'sous_secteur_id.required' => 'Le sous-secteur est obligatoire.',
            'sous_secteur_id.exists' => 'Le sous-secteur sélectionné est invalide.',
        ];
    }
}
