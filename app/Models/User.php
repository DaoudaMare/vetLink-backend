<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Organization;
use App\Models\UserType;
use Laravel\Sanctum\HasApiTokens;
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
    
    protected $fillable = [
        'id', 'firstName', 'lastName', 'email', 'tel1', 'tel2', 'user_type_id', 'password', 'organization_id',
    ];

    /**
     * Relation One-to-One : 
     * Un utilisateur appartient à un type d'utilisateur.
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * Relation One-to-One : 
     * Un utilisateur peut appartenir à une organisation.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Vérifie si l'utilisateur est un administrateur.
     */
    public function isAdmin(): bool
    {
        return $this->userType && $this->userType->title === 'Admin';
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
        // Removed old enum casts as they are no longer relevant
    ];
}
