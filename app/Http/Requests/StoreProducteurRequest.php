<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProducteurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la création d’un producteur.
     */
    public function rules(): array
    {
        return [
            'type_production'       => 'required|string|max:255',
            'mode_paiement'         => 'required|string|in:mobile_money,espece,virement',
            'secteur_activite'      => 'nullable|in:production_agricole,elevage,transformation,distribution,export,peche',
            'liens_reseaux_sociaux' => 'nullable|json',
            'description'           => 'nullable|string',
        ];
    }

    /**
     * Messages personnalisés pour chaque règle de validation.
     */
    public function messages(): array
    {
        return [
            'type_production.required'  => 'Le type de production est obligatoire.',
            'type_production.string'    => 'Le type de production doit être une chaîne de caractères.',
            'type_production.max'       => 'Le type de production ne peut pas dépasser 255 caractères.',

            'mode_paiement.required'    => 'Le mode de paiement est obligatoire.',
            'mode_paiement.in'          => 'Le mode de paiement doit être l’un des suivants : mobile_money, espèce ou virement.',

            'secteur_activite.in'       => 'Le secteur d’activité choisi est invalide.',

            'liens_reseaux_sociaux.json'=> 'Les liens des réseaux sociaux doivent être au format JSON.',

            'description.string'        => 'La description doit être une chaîne de caractères.',
        ];
    }
}
