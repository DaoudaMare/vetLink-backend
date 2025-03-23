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
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('user'),
            'adresse' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'type_utilisateur' => 'sometimes|in:consommateur,producteur',
            'abonnement' => 'sometimes|boolean'
        ];
    }
    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'type_utilisateur.in' => 'Le type d\'utilisateur doit être soit "consommateur" soit "producteur".',
            'abonnement.boolean' => 'La valeur de l\'abonnement doit être vrai ou faux.',
        ];
    }
}
