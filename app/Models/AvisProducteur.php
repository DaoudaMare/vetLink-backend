<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisProducteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'producteur_id',
        'note',
        'commentaire',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }
}
