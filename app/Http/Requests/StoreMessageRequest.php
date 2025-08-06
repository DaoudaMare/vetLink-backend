<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'conversation_id' => 'required|exists:conversations,id',
        'message' => 'nullable|string|max:1000',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf|max:10240' // 10MB max
    ];
}
}
