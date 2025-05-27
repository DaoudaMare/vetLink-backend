<?php

namespace App\Models;

use App\Enums\TypeSecteurActiviteEnum;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producteur extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'type_entite',
    'notation',
    'secteur_activite',
    'type_production',
    'mode_paiement',
    'liens_reseaux_sociaux',
    'description'
];

protected $casts = [
    'liens_reseaux_sociaux' => 'array',
     'secteur_activite' => TypeSecteurActiviteEnum::class
];

     /**
     * Un producteur est lié à un utilisateur.
     * Relation One-to-One (1-1)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Un producteur peut proposer plusieurs produits.
     * Relation One-to-Many (1-N)
     */
    public function produits()
    {
        return $this->hasMany(Produit::class, 'producteur_id');
    }
}
