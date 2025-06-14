<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organisations';

    protected $fillable = [
        'name',
        'adresse',
        'business_sector_id',
        'organization_type_id',
        'email',
        'tel1',
        'tel2',
    ];

    // Définir les relations ici si nécessaire
    public function businessSector()
    {
        return $this->belongsTo(BusinessSector::class);
    }

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class);
    }
} 