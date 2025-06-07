<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre_membres',
        'activites_principales',
        'produits_commercialises',
    ];
}
