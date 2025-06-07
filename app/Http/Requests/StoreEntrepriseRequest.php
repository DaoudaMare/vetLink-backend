<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntrepriseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_identification_fiscale' => 'required|string|max:255',
            'produits_services' => 'required|string',
            'certifications_normes' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_identification_fiscale.required' => 'Le NIF est obligatoire',
            'numero_identification_fiscale.max' => 'Le NIF ne doit pas dépasser 255 caractères',
            'produits_services.required' => 'La description des produits/services est requise',
            'certifications_normes.array' => 'Le format des certifications est invalide',
        ];
    }
}
