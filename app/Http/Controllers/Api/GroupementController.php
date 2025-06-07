<?php

namespace App\Http\Controllers\Api;

use App\Models\Groupement;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupementRequest;
use App\Models\Groupements;
use App\Models\ProfileProgress;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GroupementController extends Controller
{
    public function store(StoreGroupementRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Rechercher ou créer une nouvelle instance du modèle
        $groupement = Groupement::firstOrNew(['user_id' => $data['user_id']]);

        // Remplir les attributs avec les données validées
        $groupement->fill($data);

        // Enregistrer le modèle
        $groupement->save();

        // Recharger l'utilisateur depuis le modèle User
        $user = User::find(Auth::id());

        // Mettre à jour la progression
        ProfileProgress::updateProgress($user);

        return response()->json([
            'message' => 'Profil groupement enregistré avec succès',
            'data' => $groupement
        ], 201);
    }
}
