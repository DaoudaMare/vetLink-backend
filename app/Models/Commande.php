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
        'product_id',
        'Quantity',
        'total_price',
        'status',
        'delivery_status',
        'payment',
    ];

    /**
     * Le client qui a passé la commande.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Le produit commandé.
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }

    // public function produits()
    // {
    //     return $this->belongsToMany(Produit::class, 'commande_produit')
    //                 ->withPivot('quantity')
    //                 ->withTimestamps();
    // }

    public function payment()
    {
        return $this->hasOne(payments::class);
    }

    public function isPaid(): bool
    {
        return optional($this->payment)->status->label === 'paid';
    }

}
