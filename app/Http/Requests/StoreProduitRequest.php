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
            'nom_produit' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'quantite_disponible' => 'required|integer|min:0',
            'ventes' => 'sometimes|integer|min:0',
            'note' => 'sometimes|numeric|between:0,5',
            'producteur_id' => 'required|exists:producteurs,id',
            'secteur_id' => 'required|exists:secteurs,id',
            'sous_secteur_id' => 'required|exists:sous_secteurs,id',
            'activite_id' => 'required|exists:activites,id',
            'code_type' => 'nullable|string|max:50',
            'unite_mesure' => 'required|string|in:kg,g,litre,pièce,boîte,sac',
            'est_bio' => 'sometimes|boolean',
            'image_principale' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'images_secondaires.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'certifications' => 'nullable|array',
            'certifications.*' => 'string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            // Nom du produit
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'nom_produit.string' => 'Le nom du produit doit être une chaîne de caractères.',
            'nom_produit.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',

            // Prix
            'prix.required' => 'Le prix du produit est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',

            // Quantité
            'quantite_disponible.required' => 'La quantité disponible est obligatoire.',
            'quantite_disponible.integer' => 'La quantité doit être un nombre entier.',
            'quantite_disponible.min' => 'La quantité ne peut pas être négative.',

            // Relations
            'producteur_id.required' => 'Le producteur est obligatoire.',
            'producteur_id.exists' => 'Le producteur sélectionné est invalide.',
            'secteur_id.required' => 'Le secteur est obligatoire.',
            'secteur_id.exists' => 'Le secteur sélectionné est invalide.',
            'sous_secteur_id.required' => 'Le sous-secteur est obligatoire.',
            'sous_secteur_id.exists' => 'Le sous-secteur sélectionné est invalide.',
            'activite_id.required' => 'L\'activité est obligatoire.',
            'activite_id.exists' => 'L\'activité sélectionnée est invalide.',

            // Images
            'image_principale.required' => 'L\'image principale est obligatoire.',
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'Le format de l\'image doit être JPEG, PNG, JPG ou WEBP.',
            'image_principale.max' => 'L\'image ne peut pas dépasser 2 Mo.',
            'images_secondaires.*.image' => 'Les fichiers secondaires doivent être des images.',
            'images_secondaires.*.mimes' => 'Les formats autorisés sont JPEG, PNG, JPG ou WEBP.',
            'images_secondaires.*.max' => 'Chaque image secondaire ne peut pas dépasser 2 Mo.',

            // Autres
            'unite_mesure.required' => 'L\'unité de mesure est obligatoire.',
            'unite_mesure.in' => 'L\'unité de mesure sélectionnée est invalide.',
            'code_type.max' => 'Le code type ne peut pas dépasser 50 caractères.',
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
