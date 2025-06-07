<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActiviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'sometimes|string|max:255',
            'exemples' => 'nullable|string',
            'sous_secteur_id' => 'sometimes|exists:sous_secteurs,id'
        ];
    }

    public function messages(): array
    {
        return [
            'nom.string' => 'Le nom doit être une chaîne de caractères',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'exemples.string' => 'Les exemples doivent être au format texte',
            'sous_secteur_id.exists' => 'Le sous-secteur sélectionné est invalide',
        ];
    }
}
