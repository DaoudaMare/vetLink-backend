<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom_raison_sociale' => 'sometimes|string|max:255',
            'type_user' => 'sometimes|in:particulier,association,entreprise,startup,admin,moderateur,consommateur,groupement',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($this->route('id')),
            ],
            'telephone' => [
                'sometimes',
                'string',
                Rule::unique('users')->ignore($this->route('id')),
            ],
            'pays' => 'sometimes|string|max:255',
            'ville' => 'nullable|string|max:255',
            'coordonnees_gps' => 'nullable|string|max:255',
            'adresse_physique' => 'nullable|string|max:255',
            'photo_profil' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est déjà utilisé.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ];
    }
}
