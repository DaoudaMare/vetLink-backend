<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiements extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'montant',
        'date_paiement',
        'mode_paiement',
        'commande_id', // Clé étrangère vers la table `commandes`
    ];

    /**
     * Relation avec le modèle Commande.
     * Un paiement appartient à une commande.
     */
    public function commande()
    {
        return $this->belongsTo(commandes::class, 'commande_id');
    }
}