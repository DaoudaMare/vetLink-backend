<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\JsonResponse;

class UserTypeController extends Controller
{
    /**
     * Récupère la liste des types d'utilisateurs disponibles pour l'inscription.
     */
    public function index(): JsonResponse
    {
        $userTypes = UserType::whereNotIn('title', ['Administrateur', 'Moderateur'])->get();

        return response()->json([
            'message' => 'Types d\'utilisateurs récupérés avec succès',
            'data' => $userTypes
        ], 200);
    }
}
