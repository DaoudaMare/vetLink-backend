<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;
    // Définir les colonnes modifiables
    protected $fillable = [
        'name',
        'description',
        'categorie_id',
        'producer_id',
        'quantity',
        'price',
        'measure',
        'isbio', 
    ];

    /**
     * Le produit appartient à une catégorie.
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Le produit appartient à un producteur.
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organisationProducer(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Le produit peut avoir plusieurs images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
    
    public function getNombreCommandesAttribute()
    {
        return $this->commandes()->sum('commande_produit.quantity');
    }
}
