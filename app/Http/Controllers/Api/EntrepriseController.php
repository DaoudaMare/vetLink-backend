<?php

namespace App\Http\Controllers\Api;

use App\Models\Entreprise;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntrepriseRequest;
use App\Models\ProfileProgress;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EntrepriseController extends Controller
{
    public function store(StoreEntrepriseRequest $request)
    {

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Rechercher ou créer une nouvelle instance du modèle
        $entreprise = Entreprise::firstOrNew(['user_id' => $data['user_id']]);

        // Remplir les attributs avec les données validées
        $entreprise->fill($data);

        // Enregistrer le modèle
        $entreprise->save();

        // Recharger l'utilisateur depuis le modèle User
        $user = User::find(Auth::id());

        // Mettre à jour la progression
        ProfileProgress::updateProgress($user);

        return response()->json([
            'message' => 'Profil entreprise enregistré avec succès',
            'data' => $entreprise
        ], 201);
    }
}
