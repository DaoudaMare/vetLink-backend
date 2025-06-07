<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_membres' => 'required|integer|min:1',
            'activites_principales' => 'required|string',
            'produits_commercialises' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_membres.required' => 'Le nombre de membres est requis',
            'nombre_membres.integer' => 'Le nombre doit être un entier',
            'nombre_membres.min' => 'Le nombre minimal de membres est 1',
            'activites_principales.required' => 'Veuillez décrire les activités principales',
            'produits_commercialises.required' => 'Veuillez indiquer les produits commercialisés',
        ];
    }
}
