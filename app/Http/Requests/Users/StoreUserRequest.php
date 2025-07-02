<?php
namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tel1' => 'required|string|unique:users,tel1',
            'tel2' => 'nullable|string|unique:users,tel2',
            'address' => 'nullable|string|max:255',
            'user_type_id' => 'required|exists:user_types,id',
            'organisation_id' => 'nullable|exists:organisations,id',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'firstName.required' => 'Le prénom est obligatoire.',
            'firstName.string' => 'Le prénom doit être une chaîne de caractères.',
            'firstName.max' => 'Le prénom ne doit pas dépasser 255 caractères.',

            'lastName.required' => 'Le nom est obligatoire.',
            'lastName.string' => 'Le nom doit être une chaîne de caractères.',
            'lastName.max' => 'Le nom ne doit pas dépasser 255 caractères.',

            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'tel1.required' => 'Le numéro de téléphone principal est obligatoire.',
            'tel1.string' => 'Le numéro de téléphone principal doit être une chaîne.',
            'tel1.unique' => 'Ce numéro de téléphone principal est déjà utilisé.',

            'tel2.string' => 'Le numéro de téléphone secondaire doit être une chaîne.',
            'tel2.unique' => 'Ce numéro de téléphone secondaire est déjà utilisé.',

            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
            'address.max' => 'L\'adresse ne doit pas dépasser 255 caractères.',

            'user_type_id.required' => 'Le type d\'utilisateur est obligatoire.',
            'user_type_id.exists' => 'Le type d\'utilisateur sélectionné est invalide.',

            'organisation_id.exists' => 'L\'organisation sélectionnée est invalide.',

            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
