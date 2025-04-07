<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commandes extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'produit_id', // Clé étrangère vers la table `produits`
        'client_id',  // Clé étrangère vers la table `users`
        'valider',    // Boolean pour indiquer si la commande est validée
        'etat',       // État de la commande
        'quantite',   // Quantité commandée
    ];

    /**
     * Relation avec le modèle Produit.
     * Une commande appartient à un produit.
     */
    public function produit()
    {
        return $this->belongsTo(produits::class, 'produit_id');
    }

    /**
     * Relation avec le modèle User (client).
     * Une commande appartient à un client (utilisateur).
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}