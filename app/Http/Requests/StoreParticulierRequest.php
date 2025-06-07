<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticulierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'methodes_production' => 'required|string|max:255',
            'certifications_labels' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'methodes_production.required' => 'Les méthodes de production sont requises',
            'methodes_production.max' => 'La description ne doit pas dépasser 255 caractères',
            'certifications_labels.array' => 'Le format des certifications est invalide',
        ];
    }
}
