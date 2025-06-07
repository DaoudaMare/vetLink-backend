<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssociationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_enregistrement' => 'required|string|max:255',
            'nombre_membres' => 'required|integer|min:1',
            'activites_principales' => 'required|string',
            'produits_commercialises' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_enregistrement.required' => 'Le numéro d\'enregistrement est obligatoire',
            'numero_enregistrement.max' => 'Le numéro ne doit pas dépasser 255 caractères',
            'nombre_membres.required' => 'Le nombre de membres est requis',
            'nombre_membres.integer' => 'Le nombre doit être un entier',
            'nombre_membres.min' => 'Le nombre minimal de membres est 1',
            'activites_principales.required' => 'Veuillez décrire les activités principales',
            'produits_commercialises.required' => 'Veuillez indiquer les produits commercialisés',
        ];
    }
}
