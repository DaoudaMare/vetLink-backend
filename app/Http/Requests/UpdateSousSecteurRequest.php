<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSousSecteurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:10|unique:sous_secteurs,code,'.$this->sous_secteur,
            'description' => 'nullable|string',
            'secteur_id' => 'sometimes|exists:secteurs,id'
        ];
    }
}
