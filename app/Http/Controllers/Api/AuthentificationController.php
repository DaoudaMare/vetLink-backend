<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\Users\StoreUserRequest;

class AuthentificationController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Enregistrer un nouvel utilisateur (POST /api/users)
     */
    public function register(StoreUserRequest $request)
    {
        $user = $this->userRepository->register($request->validated());

        return response()->json([
            'message' => 'Utilisateur créé avec succès', 
            'user' => $user
        ], 201);
    }
    
    /**
     * Connexion d'un utilisateur
     */
    public function login(Request $request)
    {
        // Validation des entrées
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Appel du repository
        $response = $this->userRepository->login($credentials);

        if (!$response) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $response
        ], 200);
    }
    
    /**
     * Déconnexion de l'utilisateur
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete(); // Supprime tous les tokens de l'utilisateur
            return response()->json([
                'message' => 'Déconnexion réussie'
            ], 200);
        }

        return response()->json([
            'message' => 'Aucun utilisateur authentifié'
        ], 401);
    }

}
