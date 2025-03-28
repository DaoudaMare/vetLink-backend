<?php

namespace App\Models;

use App\Models\User;
use App\Models\Entreprise;
use App\Models\Association;
use App\Models\Particulier;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileProgress extends Model
{
    use HasFactory;

    protected $guarded = [];

     // Relation One-to-One (inverse) : Une progression de profil appartient à un utilisateur.
     public function user()
     {
         return $this->belongsTo(User::class, 'user_id');
     }
     
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($profileP) {
            $profileP->id = Str::uuid(); 
        });
    }

    public static function updateProgress(User $user)
    {
        $progress = 10; // Départ à 10%
        // Vérification en fonction du type d'utilisateur
        switch ($user->type_user) {
            case 'particulier':
                $profile = Particulier::where('user_id', $user->id)->first();
                if ($profile) {
                    if ($profile->methodes_production) $progress += 30;
                    if (!empty($profile->certifications_labels)) $progress += 20;
                }
                break;

            case 'association':
                $profile = Association::where('user_id', $user->id)->first();
                if ($profile) {
                    if ($profile->numero_enregistrement) $progress += 20;
                    if ($profile->nombre_membres > 0) $progress += 10;
                    if ($profile->activites_principales) $progress += 10;
                    if ($profile->produits_commercialises) $progress += 10;
                }
                break;

            case 'entreprise':
                $profile = Entreprise::where('user_id', $user->id)->first();
                if ($profile) {
                    if ($profile->numero_identification_fiscale) $progress += 30;
                    if ($profile->produits_services) $progress += 20;
                    if (!empty($profile->certifications_normes)) $progress += 10;
                }
                break;

            case 'groupement':
                $profile = Groupements::where('user_id', $user->id)->first();
                if ($profile) {
                    if ($profile->nombre_membres > 0) $progress += 20;
                    if ($profile->activites_principales) $progress += 15;
                    if ($profile->produits_commercialises) $progress += 15;
                }
                break;

            case 'startup':
                $profile = Startup::where('user_id', $user->id)->first();
                if ($profile) {
                    if ($profile->type_innovation) $progress += 30;
                    if (!empty($profile->investisseurs_partenaires)) $progress += 20;
                }
                break;
        }

        // Vérifier les documents validés
        $documents_valides = Document::where('user_id', $user->id)->where('status', 'approved')->count();
        if ($documents_valides > 0) {
            $progress += min(20, $documents_valides * 5); // Max 20% pour les documents
        }

        // Mise à jour de la progression
        ProfileProgress::updateOrCreate(
            ['user_id' => $user->id],
            ['completion_percentage' => min(100, $progress)] // Ne pas dépasser 100%
        );
    }


}
