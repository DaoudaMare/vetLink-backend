<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produits extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_produit',
        'description',
        'prix',
        'quantite_disponible',
        'producteur_id', // Clé étrangère vers la table `users`
    ];

    /**
     * Relation avec le modèle User (ou Producteur).
     * Un produit appartient à un utilisateur (producteur).
     */
    public function producteur()
    {
        return $this->belongsTo(User::class, 'producteur_id');
    }
}