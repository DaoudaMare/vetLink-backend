<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActiviteRequest;
use App\Http\Requests\UpdateActiviteRequest;
use App\Models\Activite;
use Illuminate\Http\JsonResponse;

class ActiviteController extends Controller
{
    /**
     * Liste toutes les activités
     */
    public function index(): JsonResponse
    {
        $activites = Activite::with(['sousSecteur.secteur'])
            ->orderBy('nom')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activites
        ]);
    }

    /**
     * Crée une nouvelle activité
     */
    public function store(StoreActiviteRequest $request): JsonResponse
    {
        $activite = Activite::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Activité créée avec succès',
            'data' => $activite->load('sousSecteur')
        ], 201);
    }

    /**
     * Affiche une activité spécifique
     */
    public function show(Activite $activite): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $activite->load(['sousSecteur.secteur'])
        ]);
    }

    /**
     * Met à jour une activité
     */
    public function update(UpdateActiviteRequest $request, Activite $activite): JsonResponse
    {
        $activite->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Activité mise à jour avec succès',
            'data' => $activite
        ]);
    }

    /**
     * Supprime une activité
     */
    public function destroy(Activite $activite): JsonResponse
    {
        if ($activite->produits()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer : des produits sont associés'
            ], 422);
        }

        $activite->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activité supprimée avec succès'
        ]);
    }

    /**
     * Récupère les produits d'une activité
     */
    public function produits(Activite $activite): JsonResponse
    {
        $produits = $activite->produits()
            ->with(['secteur', 'sousSecteur', 'producteur'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $produits
        ]);
    }
}
