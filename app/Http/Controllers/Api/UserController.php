<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(Request $request)
    {
        Auth::user()->can('viewAny', User::class);
        $perPage = $request->query('per_page', 15);
        $users = $this->userRepository->getAll($perPage);
        return response()->json([
            'message' => 'Listes des utilisateur recupéré avec succès',
            'users' => $users
        ], 200);
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
        $this->authorize('view', $user);
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
        $this->authorize('update', $user);

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
        $this->authorize('delete', $user);

        $this->userRepository->delete($user);

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ], 200);
    }
}
