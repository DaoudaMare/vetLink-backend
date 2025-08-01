<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'categorie' => new CategorieResource($this->whenLoaded('categorie')),
            'producer' => new UserResource($this->whenLoaded('producer')),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'measure' => $this->measure,
            'isbio' => $this->isbio,
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            // 'nombre_commandes' => $this->nombre_commandes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 