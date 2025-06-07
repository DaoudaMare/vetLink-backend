<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProducteurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type_production'       => 'sometimes|required|string|max:255',
            'mode_paiement'         => 'sometimes|required|string|in:mobile_money,espece,virement',
            'secteur_activite'      => 'nullable|in:production_agricole,elevage,transformation,distribution,export,peche',
            'liens_reseaux_sociaux' => 'nullable|json',
            'description'           => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'type_production.required'  => 'Le type de production est requis.',
            'mode_paiement.required'    => 'Le mode de paiement est requis.',
            'mode_paiement.in'          => 'Mode de paiement invalide.',
            'secteur_activite.in'       => 'Secteur d’activité invalide.',
            'liens_reseaux_sociaux.json'=> 'Doit être au format JSON.',
        ];
    }
}
