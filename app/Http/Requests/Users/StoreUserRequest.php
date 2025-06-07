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
        return true;
    }

     /**
     * Définition des règles de validation.
     */
    public function rules(): array
    {
        return [
            'nom_raison_sociale' => 'required|string|max:255',
            'type_user' => 'required|in:particulier,association,entreprise,startup,admin,moderateur,consommateur,groupement',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required|string|unique:users,telephone',
            'pays' => 'required|string|max:255',
            'ville' => 'nullable|string|max:255',
            'coordonnees_gps' => 'nullable|string|max:255',
            'adresse_physique' => 'nullable|string|max:255',
            'photo_profil' => 'nullable|string',
            'password' => 'required|string|min:6',

        ];
    }

    /**
     * Messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom_raison_sociale.required' => 'Le nom ou raison sociale est obligatoire.',
            'type_user.required' => 'Le type d\'utilisateur est requis.',
            'type_user.in' => 'Le type d\'utilisateur doit être particulier soit association soit entreprise soit startup soit startup soit consommateur.',
            'secteur_activite.in' => 'Le type de secteur d\'activité doit être production_agricole soit elevage soit transformation soit distribution soit export soit peche.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'pays.required' => 'Le pays est requis.',
            'photo_profil.string' => 'La photo de profil doit être une chaîne de caractères.',

        ];
    }
}
