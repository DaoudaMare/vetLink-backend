<?php

namespace App\Http\Controllers\Api;

use App\Models\Particulier;
use App\Models\User;
use App\Models\ProfileProgress;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParticulierRequest;
use Illuminate\Support\Facades\Auth;

class ParticulierController extends Controller
{
  public function store(StoreParticulierRequest $request)
{
    $data = $request->validated();
    $data['user_id'] = Auth::id();

    // Rechercher ou créer une nouvelle instance du modèle
    $particulier = Particulier::firstOrNew(['user_id' => $data['user_id']]);

    // Remplir les attributs avec les données validées
    $particulier->fill($data);

    // Enregistrer le modèle
    $particulier->save();

    // Recharger l'utilisateur depuis le modèle User
    $user = User::find(Auth::id());

    // Mettre à jour la progression
    ProfileProgress::updateProgress($user);

    return response()->json([
        'message' => 'Profil Particulier enregistré avec succès',
        'data' => $particulier
    ], 201);
}


}
