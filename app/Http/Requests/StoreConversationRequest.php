<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize()
    {
        return true; // Ou ajouter une policy plus tard
    }

    public function rules()
    {
        return [
            'user_two_id' => 'required|exists:users,id|different:auth_user_id',
        ];
    }
}
