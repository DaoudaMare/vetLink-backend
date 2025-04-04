<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom_produit',
        'description',
        'prix',
        'quantite_disponible',
        'ventes',
        'note',
        'producteur_id',
        'secteur_id',
        'sous_secteur_id',
        'activite_id',
        'code_type',
        'unite_mesure',
        'image_principale',
        'images_secondaires',
        'est_bio',
        'certifications'
    ];

    protected $casts = [
        'images_secondaires' => 'array',
        'certifications' => 'array',
        'est_bio' => 'boolean',
        'prix' => 'decimal:2',
        'note' => 'decimal:1'
    ];

    protected $appends = [
        'image_principale_url',
        'images_secondaires_urls'
    ];

    /**
     * Générer l'URL complète de l'image principale
     */
    public function getImagePrincipaleUrlAttribute()
    {
        return $this->image_principale ? asset('storage/' . $this->image_principale) : null;
    }

    /**
     * Générer les URLs complètes des images secondaires
     */
    public function getImagesSecondairesUrlsAttribute()
    {
        if (!$this->images_secondaires) {
            return null;
        }

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images_secondaires);
    }

    /**
     * Relation avec le producteur
     */
    public function producteur()
    {
        return $this->belongsTo(Producteur::class);
    }

    /**
     * Relation avec le secteur
     */
    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    /**
     * Relation avec le sous-secteur
     */
    public function sousSecteur()
    {
        return $this->belongsTo(SousSecteur::class);
    }

    /**
     * Relation avec l'activité
     */
    public function activite()
    {
        return $this->belongsTo(Activite::class);
    }

    /**
     * Relation avec les commandes
     */
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }

    /**
     * Scope pour les produits bio
     */
    public function scopeBio($query)
    {
        return $query->where('est_bio', true);
    }

    /**
     * Scope pour les produits d'un secteur spécifique
     */
    public function scopeParSecteur($query, $secteurId)
    {
        return $query->where('secteur_id', $secteurId);
    }

    /**
     * Scope pour les produits d'un sous-secteur spécifique
     */
    public function scopeParSousSecteur($query, $sousSecteurId)
    {
        return $query->where('sous_secteur_id', $sousSecteurId);
    }

    /**
     * Scope pour les produits d'une activité spécifique
     */
    public function scopeParActivite($query, $activiteId)
    {
        return $query->where('activite_id', $activiteId);
    }

    /**
     * Mettre à jour le stock après une commande
     */
    public function mettreAJourStock($quantiteVendue)
    {
        $this->quantite_disponible -= $quantiteVendue;
        $this->ventes += $quantiteVendue;
        $this->save();
    }
}
