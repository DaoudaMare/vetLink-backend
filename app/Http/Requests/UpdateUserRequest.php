<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userToUpdate = $this->route('user');
        return $this->user()->can('update', $userToUpdate);
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;
        return [
            'firstName' => 'sometimes|string|max:255',
            'lastName' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'tel1' => 'sometimes|string|max:255',
        ];
    }
}