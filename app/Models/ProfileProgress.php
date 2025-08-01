<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'completion_percentage',
        'completed_steps',
        'total_steps'
    ];

    // Le cast incorrect a été supprimé

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}