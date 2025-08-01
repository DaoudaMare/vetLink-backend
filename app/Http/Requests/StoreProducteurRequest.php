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
        return true; // Allow all users to make this request for now, adjust as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'user_type_id' => ['required', 'integer', 'exists:user_types,id'], // Assuming user_types table exists

            // Conditional validation for producer-specific fields
            'organization_name' => ['required_if:user_type_id,3', 'string', 'max:255'],
            'organization_type_id' => ['required_if:user_type_id,3', 'integer', 'exists:organization_types,id'], // Assuming organization_types table exists
            'business_sector_id' => ['required_if:user_type_id,3', 'integer', 'exists:business_sectors,id'], // Assuming business_sectors table exists
            'organization_address' => ['required_if:user_type_id,3', 'string', 'max:255'],
        ];
    }
}
