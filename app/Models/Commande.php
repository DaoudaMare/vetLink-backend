<?php

namespace App\Models;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date_commande', 'statut'
    ];

    /**
     * Une commande appartient à un utilisateur.
     * Relation One-to-Many (1-N) inverse
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Une commande peut contenir plusieurs produits.
     * Relation Many-to-Many (N-N) avec une table pivot 'commande_produit'
     */
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
                    ->withPivot('quantite') // On garde la quantité de chaque produit commandé
                    ->withTimestamps(); // On garde les dates de création/modification
    }

    
}
