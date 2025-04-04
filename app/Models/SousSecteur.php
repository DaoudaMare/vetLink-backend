<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousSecteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'secteur_id',

    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function activites()
    {
        return $this->hasMany(Activite::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    // Scope pour sous-secteurs principaux (exemple)
    public function scopePrincipaux($query)
    {
        return $query->where('est_principal', true);
    }

    // Méthode pour statistiques
    public function nombreProduitsDisponibles()
    {
        return $this->produits()->where('quantite_disponible', '>', 0)->count();
    }
}
