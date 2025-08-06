<?php
namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Autoriser cette requête.
     */
    public function authorize(): bool
    {
        return true; // Autorise tout le monde à faire cette requête
    }

    /**
     * Définition des règles de validation.
     */
    public function rules(): array
    {
        return [
            'firstName' => 'sometimes|string|max:255',
            'lastName' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('id'),
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'user_type_id' => 'sometimes|exists:user_types,id',
            'organisation_id' => 'nullable|exists:organisations,id',
            // 'abonnement' : pas présent dans ta table users, à retirer ou ajouter en base si besoin
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'firstName.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstName.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'lastName.string' => 'Le nom doit être une chaîne de caractères.',
            'lastName.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
            'address.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',

            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',

            'user_type_id.exists' => 'Le type d\'utilisateur sélectionné est invalide.',

            'organisation_id.exists' => 'L\'organisation sélectionnée est invalide.',
        ];
    }
}
