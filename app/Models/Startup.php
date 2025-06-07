<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'type_innovation',
        'investisseurs_partenaires',
    ];

    protected $casts = [
        'investisseurs_partenaires' => 'array',
    ];
}
