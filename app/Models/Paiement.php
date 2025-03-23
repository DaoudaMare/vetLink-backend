<?php

namespace App\Models;

use App\Models\Commande;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id', 'montant', 'date_paiement', 'mode_paiement'
    ];

    /**
     * Un paiement appartient à une commande.
     * Relation One-to-One (1-1) inverse
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
}
