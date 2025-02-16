<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom_produit' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'quantite_disponible' => 'required|integer|min:0',
            'producteur_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'nom_produit.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nom_produit.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',

            'prix.required' => 'Le prix du produit est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',

            'quantite_disponible.required' => 'La quantité disponible est obligatoire.',
            'quantite_disponible.integer' => 'La quantité doit être un nombre entier.',
            'quantite_disponible.min' => 'La quantité ne peut pas être négative.',


            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Le format de l\'image doit être JPEG, PNG, JPG .',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo.'
        ];
    }
}
