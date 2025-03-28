<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduitRequest extends FormRequest
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
            'nomProduit' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'sometimes|numeric|min:0',
            'quantiteDisponible' => 'sometimes|integer|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'nomProduit.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nomProduit.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',

            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',

            'quantiteDisponible.integer' => 'La quantité doit être un nombre entier.',
            'quantiteDisponible.min' => 'La quantité ne peut pas être négative.'
        ];
    }
}
