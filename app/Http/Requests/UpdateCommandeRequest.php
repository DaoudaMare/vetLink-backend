<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produits' => 'sometimes|array',
            'produits.*.id' => 'required_with:produits|exists:produits,id',
            'produits.*.quantite' => 'required_with:produits|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'produits.array' => 'Le format des produits est invalide',
            'produits.*.id.required_with' => 'L\'ID du produit est requis',
            'produits.*.id.exists' => 'Un des produits n\'existe pas',
            'produits.*.quantite.required_with' => 'La quantité est requise',
            'produits.*.quantite.integer' => 'La quantité doit être un nombre entier',
            'produits.*.quantite.min' => 'La quantité minimale est 1',
        ];
    }
}
