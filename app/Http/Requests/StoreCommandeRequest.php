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
            'product_id' => 'required|exists:produits,id',
            'Quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'L\'identifiant du client est obligatoire.',
            'customer_id.exists' => 'Le client sélectionné n\'existe pas.',
            
            'product_id.required' => 'L\'identifiant du produit est obligatoire.',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
            
            'Quantity.required' => 'La quantité est obligatoire.',
            'Quantity.integer' => 'La quantité doit être un nombre entier.',
            'Quantity.min' => 'La quantité doit être supérieure à 0.',
        ];
    }
}
