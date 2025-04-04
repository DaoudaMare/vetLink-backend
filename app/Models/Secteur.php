<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;


    protected $fillable = [
        'nom',
        'code',
        'description',

    ];

    public function sousSecteurs()
    {
        return $this->hasMany(SousSecteur::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    // Accesseur pour le nom complet (exemple)
    public function getNomCompletAttribute()
    {
        return "Secteur {$this->nom} ({$this->code})";
    }

    // Scope pour secteurs actifs (exemple)
    public function scopeActifs($query)
    {
        return $query->where('est_actif', true);
    }

}
