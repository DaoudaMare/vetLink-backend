<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProfileProgressRepository;
use Illuminate\Support\Facades\Auth;

class ProfileProgressController extends Controller
{
    protected $profileProgressRepository;

    public function __construct(ProfileProgressRepository $profileProgressRepository)
    {
        $this->profileProgressRepository = $profileProgressRepository;
    }

    public function show(string $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Autorisation: Seul l'utilisateur lui-même ou un admin peut voir sa progression
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $progress = $this->profileProgressRepository->calculateAndUpdateProgress($user);
        return response()->json($progress, 200);
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Autorisation: Seul l'utilisateur lui-même ou un admin peut déclencher le recalcul
        if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // La mise à jour du profil utilisateur (ex: $user->update($request->all())) 
        // devrait être gérée par un autre contrôleur, comme UserController.
        // Ici, nous recalculons simplement la progression après une mise à jour.

        $progress = $this->profileProgressRepository->calculateAndUpdateProgress($user);

        return response()->json([
            'message' => 'Progression du profil mise à jour avec succès',
            'progress' => $progress
        ], 200);
    }
}