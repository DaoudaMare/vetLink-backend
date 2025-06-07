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
    $progress = 20;

    if ($profile = Particulier::where('user_id', $user->id)->first()) {
        if (!empty($profile->methodes_production)) $progress += 30;

        $certifs = is_string($profile->certifications_labels)
            ? json_decode($profile->certifications_labels, true)
            : $profile->certifications_labels;

        if (is_array($certifs) && count($certifs) > 0) $progress += 20;
    } elseif ($profile = Association::where('user_id', $user->id)->first()) {
        if ($profile->numero_enregistrement) $progress += 20;
        if ($profile->nombre_membres > 0) $progress += 10;
        if ($profile->activites_principales) $progress += 10;
        if ($profile->produits_commercialises) $progress += 10;
    } elseif ($profile = Entreprise::where('user_id', $user->id)->first()) {
        if ($profile->numero_identification_fiscale) $progress += 30;
        if ($profile->produits_services) $progress += 20;

        $certifs = is_string($profile->certifications_normes)
            ? json_decode($profile->certifications_normes, true)
            : $profile->certifications_normes;

        if (is_array($certifs) && count($certifs) > 0) $progress += 10;
    } elseif ($profile = Groupement::where('user_id', $user->id)->first()) {
        if ($profile->nombre_membres > 0) $progress += 20;
        if ($profile->activites_principales) $progress += 15;
        if ($profile->produits_commercialises) $progress += 15;
    } elseif ($profile = Startup::where('user_id', $user->id)->first()) {
        if ($profile->type_innovation) $progress += 30;

        $invest = is_string($profile->investisseurs_partenaires)
            ? json_decode($profile->investisseurs_partenaires, true)
            : $profile->investisseurs_partenaires;

        if (is_array($invest) && count($invest) > 0) $progress += 20;
    }

    // Documents validés
    $docs = Document::where('user_id', $user->id)->where('status', 'approved')->count();
    if ($docs > 0) $progress += min(20, $docs * 5);

    ProfileProgress::updateOrCreate(
        ['user_id' => $user->id],
        ['completion_percentage' => min(100, $progress)]
    );
}



}
