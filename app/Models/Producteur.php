<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        // Ajoutez d'autres champs spécifiques au producteur ici
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
} 