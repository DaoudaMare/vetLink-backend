<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Users\StoreUserRequest;
use App\Repositories\ProfileProgressRepository;
use App\Enums\TypeUserEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

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
     * Enregistre un nouvel utilisateur (POST /api/users)
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        $userData = $request->validated();

        try {
            // Création du user via le repository
            $user = $this->userRepository->register($userData);

            if (!$user || !$user->id) {
                return response()->json(['message' => 'Erreur lors de la création de l\'utilisateur'], 500);
            }

            // Initialisation du profil progress
            $this->profileProgressRepository->createProfileProgress([
                'user_id' => $user->id
            ]);

            // Gestion correcte de l'enum
            $userType = $user->type_user instanceof TypeUserEnum
                ? $user->type_user->value
                : $user->type_user;

            $isProducer = in_array($userType, [
                TypeUserEnum::Particulier->value,
                TypeUserEnum::Association->value,
                TypeUserEnum::Entreprise->value,
                TypeUserEnum::Startup->value
            ]);

            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $user,
                'next_step' => $isProducer ? 'complete_producer_profile' : null
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement utilisateur : ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur serveur',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Connexion d'un utilisateur
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        return $this->userRepository->inLogin($credentials);
    }

    /**
     * Déconnexion d'un utilisateur
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete();

            return response()->json([
                'message' => 'Déconnexion réussie'
            ], 200);
        }

        return response()->json([
            'message' => 'Aucun utilisateur authentifié'
        ], 401);
    }
}
