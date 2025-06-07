<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_identification_fiscale',
        'produits_services',
        'certifications_normes',
    ];

    protected $casts = [
        'certifications_normes' => 'array',
    ];
}
