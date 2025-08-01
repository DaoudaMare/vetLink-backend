<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandeResource extends JsonResource
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
            'num' => $this->num,
            'customer' => new UserResource($this->whenLoaded('customer')),
            'products' => ProduitResource::collection($this->whenLoaded('produits')),
            'total_price' => $this->total_price,
            'status' => $this->status,
            'delivery_status' => $this->delivery_status,
            'payment' => $this->payment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 