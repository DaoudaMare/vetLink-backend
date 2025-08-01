<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'name',
        'type',
        'product_id',
        'path'
    ];

    public function product()
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }

    /**
     * Get the full URL for the image.
     */
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->path);
    }
}
