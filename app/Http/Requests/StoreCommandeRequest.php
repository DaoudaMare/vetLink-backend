<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produits' => 'required|array',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'produits.required' => 'La liste des produits est obligatoire',
            'produits.array' => 'Le format des produits est invalide',
            'produits.*.id.required' => 'L\'ID du produit est requis',
            'produits.*.id.exists' => 'Un des produits n\'existe pas',
            'produits.*.quantite.required' => 'La quantité est requise',
            'produits.*.quantite.integer' => 'La quantité doit être un nombre entier',
            'produits.*.quantite.min' => 'La quantité minimale est 1',
        ];
    }
}
