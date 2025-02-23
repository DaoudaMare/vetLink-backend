<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Commande;
use App\Models\Producteur;
use App\Enums\TypeUserEnum;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\TypeSecteurActiviteEnum;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($user){
            if(!$user)
            {
                $user->id = Str::uuid();
            }
        });
    }
    /**
     * Un utilisateur peut être un producteur.
     * Relation One-to-One (1-1)
     */

    public function producteur()
    {
        return $this->hasOne(Producteur::class, 'user_id');
    }

    /**
     * Un utilisateur peut passer plusieurs commandes.
     * Relation One-to-Many (1-N)
     */
    
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'user_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type_user' => TypeUserEnum::class,
        'secteur_activite' => TypeSecteurActiviteEnum::class,
        'id' => 'string'
    ];
}
