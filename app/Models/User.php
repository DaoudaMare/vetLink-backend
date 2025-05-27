<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Commande;
use App\Models\Entreprise;
use App\Models\Producteur;
use App\Enums\TypeUserEnum;
use App\Models\Association;
use App\Models\Groupements;
use Illuminate\Support\Str;
use App\Models\ProfileProgress;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
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

    protected $fillable = [
      'id',  'nom_raison_sociale', 'type_user',  'email', 'telephone',  'password',
        'pays', 'ville', 'coordonnees_gps', 'adresse_physique', 'photo_profil',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

   protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (empty($model->{$model->getKeyName()})) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();
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
     * Relation One-to-One (1-1) :
     * Un utilisateur possède une progression de profil.
     */
    public function profileProgress()
    {
        return $this->hasOne(ProfileProgress::class, 'user_id');
    }

    /**
     * Relation One-to-One (1-1) :
     * Un utilisateur peut être une association.
     */
    public function association()
    {
        return $this->hasOne(Association::class);
    }

    /**
     * Relation One-to-One (1-1) :
     * Un utilisateur peut être une entreprise.
     */
    public function entreprise()
    {
        return $this->hasOne(Entreprise::class);
    }

    /**
     * Relation One-to-One (1-1) :
     * Un utilisateur peut être une startup.
     */
    public function startup()
    {
        return $this->hasOne(Startup::class);
    }

    /**
     * Relation One-to-One (1-1) :
     * Un utilisateur peut être un groupement.
     */
    public function groupement()
    {
        return $this->hasOne(Groupements::class);
    }


    /**
     * Un utilisateur peut passer plusieurs commandes.
     * Relation One-to-Many (1-N)
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
     /**
     * Un utilisateur peut updload plusieurs documents.
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
        'id' => 'string'
    ];



}
