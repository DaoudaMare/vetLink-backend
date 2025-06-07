<?php

namespace App\Http\Controllers\Api;

use App\Models\Association;
use App\Models\User;
use App\Models\ProfileProgress;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssociationRequest;
use Illuminate\Support\Facades\Auth;

class AssociationController extends Controller
{
    public function store(StoreAssociationRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // Rechercher ou créer une nouvelle instance du modèle
        $association = Association::firstOrNew(['user_id' => $data['user_id']]);

        // Remplir les attributs avec les données validées
        $association->fill($data);

        // Enregistrer le modèle
        $association->save();

        // Recharger l'utilisateur depuis le modèle User
        $user = User::find(Auth::id());

        // Mettre à jour la progression
        ProfileProgress::updateProgress($user);

        return response()->json([
            'message' => 'Profil Association enregistré avec succès',
            'data' => $association
        ], 201);
    }
}
