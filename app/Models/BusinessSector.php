<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSector extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Si vous avez des relations pour BusinessSector, vous pouvez les définir ici
} 