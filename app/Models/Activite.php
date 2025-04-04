<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'exemples',
        'sous_secteur_id'
    ];


    /**
     * Relation avec le sous-secteur parent
     */
    public function sousSecteur(): BelongsTo
    {
        return $this->belongsTo(SousSecteur::class);
    }

    /**
     * Relation avec les produits de cette activité
     */
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    /**
     * Accesseur pour les exemples (convertit en array si c'est une string JSON)
     */
    public function getExemplesAttribute($value)
    {
        if (is_string($value) && json_decode($value)) {
            return json_decode($value, true);
        }
        return $value;
    }


    /**
     * Mutateur pour les exemples (convertit array en string JSON si nécessaire)
     */
    public function setExemplesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['exemples'] = json_encode($value);
        } else {
            $this->attributes['exemples'] = $value;
        }
    }
}
