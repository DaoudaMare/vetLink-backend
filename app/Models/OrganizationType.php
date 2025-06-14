<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    use HasFactory;

    protected $table = 'organisation_types';

    protected $fillable = [
        'name',
        'product_name',
    ];

    // Si vous avez des relations pour OrganizationType, vous pouvez les définir ici
} 