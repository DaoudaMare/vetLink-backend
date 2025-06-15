<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'tel1' => $this->tel1,
            'tel2' => $this->tel2,
            'user_type' => $this->whenLoaded('userType', function() {
                return [
                    'id' => $this->userType->id,
                    'title' => $this->userType->title
                ];
            }),
            'organization' => $this->whenLoaded('organization', function() {
                return [
                    'id' => $this->organization->id,
                    'name' => $this->organization->name,
                    'adresse' => $this->organization->adresse,
                    'email' => $this->organization->email,
                    'tel1' => $this->organization->tel1,
                    'tel2' => $this->organization->tel2
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
} 