<?php

namespace App\Http\Controllers\Api;

use App\Models\Startup;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStartupRequest;
use App\Models\ProfileProgress;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StartupController extends Controller
{
    public function store(StoreStartupRequest $request)
    {
       $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Rechercher ou créer une nouvelle instance du modèle
        $startup = Startup::firstOrNew(['user_id' => $data['user_id']]);

        // Remplir les attributs avec les données validées
        $startup->fill($data);

        // Enregistrer le modèle
        $startup->save();

        // Recharger l'utilisateur depuis le modèle User
        $user = User::find(Auth::id());

        // Mettre à jour la progression
        ProfileProgress::updateProgress($user);

        return response()->json([
            'message' => 'Profil startup enregistré avec succès',
            'data' => $startup
        ], 201);
    }
}
