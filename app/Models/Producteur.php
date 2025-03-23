<?php

namespace App\Models;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'localisation', 'notation', 'type_production', 'certifications', 'mode_paiement'
    ];

    protected $casts = [
        'certifications' => 'array',
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
