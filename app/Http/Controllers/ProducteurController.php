<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProducteurRequest;
use App\Models\Producteur;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Enums\TypeUserEnum;

class ProducteurController extends Controller
{
    /**
     * Enregistre un nouveau producteur
     */
    public function store(StoreProducteurRequest $request): JsonResponse
    {
        $user = Auth::user();

        // Récupération du type utilisateur sous forme de string
        $userType = $user->type_user instanceof TypeUserEnum
            ? $user->type_user->value
            : $user->type_user;

        // Définir les types d'utilisateurs autorisés à devenir producteurs
        $allowedTypes = [
            TypeUserEnum::Particulier->value,
            TypeUserEnum::Association->value,
            TypeUserEnum::Entreprise->value,
            TypeUserEnum::Startup->value
        ];

        // Refuser si le type d'utilisateur n'est pas autorisé
        if (!in_array($userType, $allowedTypes)) {
            return response()->json([
                'error' => 'Votre type de compte (' . $userType . ') ne permet pas de devenir producteur.',
                'allowed_types' => $allowedTypes
            ], 403);
        }

        try {
            // Création du producteur
            $producer = Producteur::create([
                'user_id' => $user->id,
                'type_entite' => $userType,
                'localisation' => $request->safe()->localisation,
                'type_production' => $request->safe()->type_production,
                'notation' => 0, // Note par défaut
                'mode_paiement' => $request->safe()->mode_paiement,
                'certifications' => $request->safe()->certifications ?? null
            ]);

            return response()->json([
                'message' => 'Profil producteur créé avec succès.',
                'data' => $producer->load('user') // Charge les infos de l'utilisateur associé
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du producteur : ' . $e->getMessage());

            return response()->json([
                'error' => 'Erreur lors de la création du profil producteur.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
