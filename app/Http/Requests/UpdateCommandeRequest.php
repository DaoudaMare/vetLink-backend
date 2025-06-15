<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommandeRequest extends FormRequest
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
            'customer_id' => 'sometimes|exists:users,id',
            'product_id' => 'sometimes|exists:produits,id',
            'Quantity' => 'sometimes|integer|min:1',
            'status' => 'sometimes|integer|min:0|max:3',
            'delivery_status' => 'sometimes|integer|min:0|max:3',
            'payment' => 'sometimes|integer|min:0|max:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'Le client sélectionné n\'existe pas.',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'Quantity.integer' => 'La quantité doit être un nombre entier.',
            'Quantity.min' => 'La quantité doit être supérieure à 0.',
            'status.integer' => 'Le statut doit être un nombre entier.',
            'status.min' => 'Le statut doit être supérieur ou égal à 0.',
            'status.max' => 'Le statut doit être inférieur ou égal à 3.',
            'delivery_status.integer' => 'Le statut de livraison doit être un nombre entier.',
            'delivery_status.min' => 'Le statut de livraison doit être supérieur ou égal à 0.',
            'delivery_status.max' => 'Le statut de livraison doit être inférieur ou égal à 3.',
            'payment.integer' => 'Le statut de paiement doit être un nombre entier.',
            'payment.min' => 'Le statut de paiement doit être supérieur ou égal à 0.',
            'payment.max' => 'Le statut de paiement doit être inférieur ou égal à 1.',
        ];
    }
}
