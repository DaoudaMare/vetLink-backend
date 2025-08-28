<?php

namespace App\Models;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'num',
        'customer_id',
        'total_price',
        'status',
        'delivery_status',
        'payment',
        'created_at',
    ];

    /**
     * Le client qui a passé la commande.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Le produit commandé. (DEPRECATED)
     */
    // public function produit(): BelongsTo
    // {
    //     return $this->belongsTo(Produit::class, 'product_id');
    // }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
                    ->withPivot('quantity', 'status')
                    ->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(payments::class);
    }

    public function isPaid(): bool
    {
        return optional($this->payment)->status->label === 'paid';
    }

    /**
     * Calcule le statut global de la commande basé sur les statuts des produits.
     * 0: En attente, 1: Confirmé, 2: En préparation, 3: Expédié, 4: Livré
     *
     * Logique simplifiée:
     * - Si tous les produits sont Livrés (4), la commande est Livrée.
     * - Si au moins un produit est En attente (0), la commande est En attente.
     * - Sinon, le statut est le minimum des statuts des produits (ex: si un est Confirmé et l'autre Expédié, la commande est Confirmée).
     */
    public function getOverallStatusAttribute(): int
    {
        $productStatuses = $this->produits->pluck('pivot.status');

        if ($productStatuses->isEmpty()) {
            return $this->status; // Fallback to main order status if no products or no pivot status
        }

        // Si tous les produits sont Livrés (4)
        if ($productStatuses->every(fn ($status) => $status === 4)) {
            return 4; // Livré
        }

        // Si au moins un produit est En attente (0)
        if ($productStatuses->contains(0)) {
            return 0; // En attente
        }

        // Sinon, le statut global est le minimum des statuts des produits
        // Cela signifie que la commande est au moins au statut le plus bas de ses produits.
        return $productStatuses->min();
    }

    public function recalculateOverallStatus(): void
    {
        // Ensure the 'produits' relationship is loaded to access pivot statuses
        $this->load('produits'); 
        $newOverallStatus = $this->overall_status; // Use the accessor to get the calculated status

        if ($this->status !== $newOverallStatus) {
            $this->status = $newOverallStatus;
            $this->save();
        }
    }

}
