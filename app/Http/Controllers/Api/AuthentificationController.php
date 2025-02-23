<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Requests\Users\StoreUserRequest;
use App\Repositories\ProfileProgressRepository;

class AuthentificationController extends Controller
{
    protected $userRepository;
    protected $profileProgressRepository;

    public function __construct(UserRepository $userRepository, ProfileProgressRepository $profileProgressRepository)
    {
        $this->userRepository = $userRepository;
        $this->profileProgressRepository = $profileProgressRepository;
        
    }

    /**
     * Enregistrer un nouvel utilisateur (POST /api/users)
     */
    public function register(StoreUserRequest $request)
    {
        $user = $this->userRepository->register($request->validated());
        $this->profileProgressRepository->createProfileProgress([
            'user_id' => $user->id
        ]);
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
            'password' => 'required|string|min:6'
        ]);
    
        // Tentative de connexion via le repository
        $token = $this->userRepository->login($credentials);
    
        if (!$token) {
            return response()->json([
                'message' => 'Identifiants incorrects'
            ], 401);
        }
    
        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => Auth::user() // Ajout pour renvoyer les infos de l'utilisateur connecté
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
