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

    protected $casts = [
        'completed_steps' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function updateProgress(User $user)
    {
        $profileProgress = self::where('user_id', $user->id)->first();
        
        if (!$profileProgress) {
            return;
        }

        // Logique pour calculer le pourcentage de progression
        $totalSteps = 5; // Nombre total d'étapes
        $completedSteps = 0;

        // Vérifier les étapes complétées
        if ($user->name) $completedSteps++;
        if ($user->email) $completedSteps++;
        if ($user->phone) $completedSteps++;
        if ($user->address) $completedSteps++;
        if ($user->business_sector_id) $completedSteps++;

        $completionPercentage = ($completedSteps / $totalSteps) * 100;

        $profileProgress->update([
            'completion_percentage' => $completionPercentage,
            'completed_steps' => $completedSteps,
            'total_steps' => $totalSteps
        ]);
    }
} 