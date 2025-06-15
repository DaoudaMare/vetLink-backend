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
            'name' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'producer_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric',
            'price' => 'required|integer',
            'measure' => 'nullable|in:kg,g,L,unité',
            'isbio' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.string' => 'Le nom du produit doit être une chaîne de caractères.',

            'price.required' => 'Le prix du produit est obligatoire.',
            'price.integer' => 'Le prix doit être un nombre entier.',

            'quantity.required' => 'La quantité disponible est obligatoire.',
            'quantity.numeric' => 'La quantité doit être un nombre.',

            'producer_id.required' => 'Le producteur est obligatoire.',
            'producer_id.exists' => 'Le producteur sélectionné est invalide.',
            
            'categorie_id.required' => 'La catégorie est obligatoire.',
            'categorie_id.exists' => 'La catégorie sélectionnée est invalide.',

            'image_principale.required' => 'L\'image principale est obligatoire.',
            'image_principale.image' => 'Le fichier doit être une image.',
            'image_principale.mimes' => 'Le format de l\'image doit être JPEG, PNG, JPG ou WEBP.',
            'image_principale.max' => 'L\'image ne peut pas dépasser 2 Mo.',
            
            'images_secondaires.*.image' => 'Les fichiers secondaires doivent être des images.',
            'images_secondaires.*.mimes' => 'Les formats autorisés sont JPEG, PNG, JPG ou WEBP.',
            'images_secondaires.*.max' => 'Chaque image secondaire ne peut pas dépasser 2 Mo.',

            'measure.in' => 'L\'unité de mesure sélectionnée est invalide.',
            'isbio.boolean' => 'La valeur bio doit être vrai ou faux.'
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
