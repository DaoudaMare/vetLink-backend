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
            'nom_produit' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'sometimes|numeric|min:0',
            'quantite_disponible' => 'sometimes|integer|min:0',
            'ventes' => 'sometimes|integer|min:0',
            'note' => 'sometimes|numeric|between:0,5',
            'producteur_id' => 'sometimes|exists:producteurs,id',
            'secteur_id' => 'sometimes|exists:secteurs,id',
            'sous_secteur_id' => 'sometimes|exists:sous_secteurs,id',
            'activite_id' => 'sometimes|exists:activites,id',
            'code_type' => 'nullable|string|max:50',
            'unite_mesure' => 'sometimes|string|in:kg,g,litre,pièce,boîte,sac',
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
            // Nom du produit
            'nom_produit.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nom_produit.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',

            // Prix
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',

            // Quantité
            'quantite_disponible.integer' => 'La quantité doit être un nombre entier.',
            'quantite_disponible.min' => 'La quantité ne peut pas être négative.',

            // Ventes
            'ventes.integer' => 'Le nombre de ventes doit être un entier.',
            'ventes.min' => 'Le nombre de ventes ne peut pas être négatif.',

            // Note
            'note.numeric' => 'La note doit être un nombre.',
            'note.between' => 'La note doit être entre 0 et 5.',

            // Relations
            'producteur_id.exists' => 'Le producteur sélectionné est invalide.',
            'secteur_id.exists' => 'Le secteur sélectionné est invalide.',
            'sous_secteur_id.exists' => 'Le sous-secteur sélectionné est invalide.',
            'activite_id.exists' => 'L\'activité sélectionnée est invalide.',

            // Images
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'Le format de l\'image doit être JPEG, PNG, JPG ou WEBP.',
            'image_principale.max' => 'L\'image ne peut pas dépasser 2 Mo.',
            'images_secondaires.array' => 'Les images secondaires doivent être dans un tableau.',
            'images_secondaires.*.image' => 'Les fichiers secondaires doivent être des images.',
            'images_secondaires.*.mimes' => 'Les formats autorisés sont JPEG, PNG, JPG ou WEBP.',
            'images_secondaires.*.max' => 'Chaque image secondaire ne peut pas dépasser 2 Mo.',

            // Autres
            'unite_mesure.in' => 'L\'unité de mesure sélectionnée est invalide.',
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
        // Conversion des noms de champs camelCase vers snake_case si nécessaire
        $this->merge([
            'nom_produit' => $this->nom_produit ?? $this->nomProduit ?? null,
            'quantite_disponible' => $this->quantite_disponible ?? $this->quantiteDisponible ?? null
        ]);

        // Convertit les certifications en array si c'est une string JSON
        if ($this->certifications && is_string($this->certifications)) {
            $this->merge([
                'certifications' => json_decode($this->certifications, true)
            ]);
        }
    }
}
