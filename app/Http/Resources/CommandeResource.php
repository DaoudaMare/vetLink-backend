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
            'products' => $this->whenLoaded('produits', function() use ($request) {
                $user = $request->user();
                $filteredProduits = $this->produits;

                // If the user is a producer, filter to show only their products
                if ($user && $user->isProducer()) {
                    $filteredProduits = $this->produits->filter(fn($produit) => $produit->producer_id === $user->id);
                }

                return $filteredProduits->map(function($produit) {
                    return [
                        'id' => $produit->id,
                        'name' => $produit->name,
                        'price' => $produit->price,
                        'quantity' => $produit->pivot->quantity,
                        'status' => $produit->pivot->status,
                        'status_label' => $this->getProductStatusLabel($produit->pivot->status),
                        'subtotal' => $produit->price * $produit->pivot->quantity,
                        'image' => $produit->images->first()?->image_url ?? null,
                        'category' => $produit->categorie?->name,
                        'producer' => $produit->producer?->name,
                    ];
                });
            }),
            'total_price' => $this->total_price,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'delivery_status' => $this->delivery_status,
            'delivery_status_label' => $this->getDeliveryStatusLabel(),
            'payment' => $this->payment,
            'payment_label' => $this->payment ? 'Payé' : 'Non payé',
            'items_count' => $this->whenLoaded('produits', function() {
                return $this->produits->sum('pivot.quantity');
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
    
    private function getStatusLabel(): string
    {
        return match($this->status) {
            0 => 'En attente',
            1 => 'Confirmée',
            2 => 'En préparation',
            3 => 'Expédiée',
            4 => 'Livrée',
            5 => 'Annulée',
            default => 'Inconnu'
        };
    }
    
    private function getDeliveryStatusLabel(): string
    {
        return match($this->delivery_status) {
            0 => 'Non livrée',
            1 => 'En cours de livraison',
            2 => 'Livrée',
            3 => 'Échec de livraison',
            default => 'Inconnu'
        };
    }
    
    private function getProductStatusLabel(int $status): string
    {
        return match($status) {
            0 => 'En attente',
            1 => 'Confirmé',
            2 => 'En préparation',
            3 => 'Expédié',
            4 => 'Livré',
            default => 'Inconnu'
        };
    }
} 