<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommandeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:users,id',
            'produits' => 'required|array|min:1',
            'produits.*.product_id' => 'required|exists:produits,id',
            'produits.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'L\'identifiant du client est obligatoire.',
            'customer_id.exists' => 'Le client sélectionné n\'existe pas.',
            
            'produits.required' => 'Au moins un produit est requis.',
            'produits.array' => 'Les produits doivent être fournis sous forme de tableau.',
            'produits.min' => 'Au moins un produit doit être commandé.',
            
            'produits.*.product_id.required' => 'L\'identifiant du produit est obligatoire.',
            'produits.*.product_id.exists' => 'Un des produits sélectionnés n\'existe pas.',
            
            'produits.*.quantity.required' => 'La quantité est obligatoire pour chaque produit.',
            'produits.*.quantity.integer' => 'La quantité doit être un nombre entier.',
            'produits.*.quantity.min' => 'La quantité doit être supérieure à 0.',
        ];
    }
}
