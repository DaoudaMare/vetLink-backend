<?php

namespace App\Models;

use App\Models\Commande;
use App\Models\Producteur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_produit', 'description', 'prix', 'quantite_disponible',   'producteur_id','image'
    ];

    // Générer l'URL complète de l'image
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    /**
     * Un produit appartient à un producteur.
     * Relation One-to-Many (1-N) inverse
     */
    public function producteur()
    {
        return $this->belongsTo(Producteur::class, 'producteur_id');
    }

    /**
     * Un produit peut être présent dans plusieurs commandes.
     * Relation Many-to-Many (N-N) avec une table pivot 'commande_produit'
     */
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantite') // On ajoute la quantité du produit dans la commande
                    ->withTimestamps(); // Pour enregistrer les dates de création/modification
    }
}
