<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Organization;
use App\Models\UserType;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
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
        // Charger la relation userType si elle n'est pas déjà chargée
        if (!$this->relationLoaded('userType')) {
            $this->load('userType');
        }

        return $this->userType && $this->userType->title === 'Admin';
    }

    /**
     * Vérifie si l'utilisateur peut accéder au panel Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Charger la relation userType si elle n'est pas déjà chargée
        if (!$this->relationLoaded('userType')) {
            $this->load('userType');
        }

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

    public function getUserNameAttribute(): string
    {
        return trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? '')) ?: ($this->email ?? 'Utilisateur');
    }

    public function getFilamentName(): string
    {
        return $this->getUserNameAttribute();
    }



public function uploadProfilePhoto($file)
{
    // Validation supplémentaire du fichier
    if (!$file->isValid()) {
        throw new \Exception('Fichier image invalide');
    }

    $directory = 'profile-photos/' . ($this->userType->is_producer ? 'producers' : 'clients');

    // Suppression sécurisée de l'ancienne photo
    if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
        Storage::disk('public')->delete($this->profile_photo_path);
    }

    // Stockage avec nom de fichier unique
    $filename = 'user-' . $this->id . '-' . time() . '.' . $file->extension();
    $path = $file->storeAs($directory, $filename, 'public');

    // Mise à jour atomique
    $this->forceFill(['profile_photo_path' => $path])->save();

    return $path;
}

public function getPhotoUrlAttribute()
{
    if (!$this->profile_photo_path) {
        return $this->getDefaultAvatarUrl();
    }

    return Storage::disk('public')->exists($this->profile_photo_path)
        ? Storage::url($this->profile_photo_path)
        : $this->getDefaultAvatarUrl();
}

protected function getDefaultAvatarUrl()
{
    return asset($this->userType->is_producer
        ? 'images/default-farmer.png'
        : 'images/default-customer.png');
}
}
