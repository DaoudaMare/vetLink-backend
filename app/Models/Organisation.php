<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'adresse',
        'business_sector_id',
        'organization_type_id',
        'email',
        'tel1',
        'tel2',
    ];
}
