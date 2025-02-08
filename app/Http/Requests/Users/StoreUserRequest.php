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
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'adresse' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'type_utilisateur' => 'required|in:consommateur,producteur',
            'abonnement' => 'boolean'
        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'type_utilisateur.required' => 'Le type d\'utilisateur est requis.',
            'type_utilisateur.in' => 'Le type d\'utilisateur doit être soit "consommateur" soit "producteur".',
            'abonnement.boolean' => 'La valeur de l\'abonnement doit être vrai ou faux.',
        ];
    }
}
