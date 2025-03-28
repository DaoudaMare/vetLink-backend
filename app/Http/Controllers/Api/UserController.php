<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\Users\UpdateUserRequest;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    
    /**
     * Liste des utilisateurs (GET /api/users)
     */
    public function index()
    {
        $user = $this->userRepository->getAll();
        return response()->json([
            'message' => 'Listes des utilisateur recupéré avec succès', 
            'user' => $user
        ], 201);
    }

    /**
     * Afficher un utilisateur spécifique (GET /api/users/{id})
     */
    public function show(string $id)
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Mettre à jour un utilisateur (PUT /api/users/{id})
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $updatedUser = $this->userRepository->update($user, $request->validated());

        return response()->json([
            'message' => 'Utilisateur mis à jour', 
            'user' => $updatedUser
        ], 200);
    }


    /**
     * Supprimer un utilisateur (DELETE /api/users/{id})
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $this->userRepository->delete($user);

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ], 200);
    }
}
