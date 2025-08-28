<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $currentUser = $this->user();
        $userId = $this->route('id');
        
        // Un utilisateur peut modifier son propre profil ou un admin peut modifier n'importe quel profil
        return $currentUser->id == $userId || $currentUser->isAdmin();
    }

    public function rules(): array
    {
        // Le paramètre de route est 'id', pas 'user'
        $userId = $this->route('id');
        return [
            'firstName' => 'sometimes|string|max:255',
            'lastName' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'tel1' => 'sometimes|string|max:255',
        ];
    }
}