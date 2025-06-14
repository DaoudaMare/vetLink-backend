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
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|integer|min:0',
            'quantite_disponible' => 'sometimes|integer|min:0',
            'ventes' => 'sometimes|integer|min:0',
            'note' => 'sometimes|numeric|between:0,5',
            'producteur_id' => 'sometimes|exists:producteurs,id',
            'categorie_id' => 'sometimes|exists:categories,id',
            'code_type' => 'nullable|string|max:50',
            'measure' => 'sometimes|string|in:kg,g,L,unité',
            'est_bio' => 'sometimes|boolean',
            'image_principale' => 'sometimes|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images_secondaires' => 'sometimes|array',
            'images_secondaires.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',

            'price.integer' => 'Le prix doit être un nombre entier.',
            'price.min' => 'Le prix ne peut pas être négatif.',

            'quantite_disponible.integer' => 'La quantité doit être un nombre entier.',
            'quantite_disponible.min' => 'La quantité ne peut pas être négative.',

            'ventes.integer' => 'Le nombre de ventes doit être un entier.',
            'ventes.min' => 'Le nombre de ventes ne peut pas être négatif.',

            'note.numeric' => 'La note doit être un nombre.',
            'note.between' => 'La note doit être entre 0 et 5.',

            'producteur_id.exists' => 'Le producteur sélectionné est invalide.',
            'categorie_id.exists' => 'La catégorie sélectionnée est invalide.',

            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'Le format de l\'image doit être JPEG, PNG, JPG ou WEBP.',
            'image_principale.max' => 'L\'image ne peut pas dépasser 2 Mo.',
            'images_secondaires.array' => 'Les images secondaires doivent être dans un tableau.',
            'images_secondaires.*.image' => 'Les fichiers secondaires doivent être des images.',
            'images_secondaires.*.mimes' => 'Les formats autorisés sont JPEG, PNG, JPG ou WEBP.',
            'images_secondaires.*.max' => 'Chaque image secondaire ne peut pas dépasser 2 Mo.',

            'measure.in' => 'L\'unité de mesure sélectionnée est invalide.',
            'code_type.max' => 'Le code type ne peut pas dépasser 50 caractères.',
            'certifications.array' => 'Les certifications doivent être dans un tableau.',
            'certifications.*.max' => 'Chaque certification ne peut pas dépasser 255 caractères.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convertit les certifications en array si c'est une string JSON
        if ($this->certifications && is_string($this->certifications)) {
            $this->merge([
                'certifications' => json_decode($this->certifications, true)
            ]);
        }
    }
}
