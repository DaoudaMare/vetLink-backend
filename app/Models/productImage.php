<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_image';

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
}
