<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSousSecteurRequest;
use App\Http\Requests\UpdateSousSecteurRequest;
use App\Models\SousSecteur;
use Illuminate\Http\JsonResponse;

class SousSecteurController extends Controller
{
    /**
     * Liste tous les sous-secteurs avec leurs activités
     */
    public function index(): JsonResponse
    {
        $sousSecteurs = SousSecteur::with(['secteur', 'activites'])
            ->orderBy('nom')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sousSecteurs
        ]);
    }

    /**
     * Crée un nouveau sous-secteur
     */
    public function store(StoreSousSecteurRequest $request): JsonResponse
    {
        $sousSecteur = SousSecteur::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Sous-secteur créé avec succès',
            'data' => $sousSecteur->load('secteur')
        ], 201);
    }

    /**
     * Affiche un sous-secteur spécifique
     */
    public function show(SousSecteur $sousSecteur): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $sousSecteur->load(['secteur', 'activites'])
        ]);
    }

    /**
     * Met à jour un sous-secteur
     */
    public function update(UpdateSousSecteurRequest $request, SousSecteur $sousSecteur): JsonResponse
    {
        $sousSecteur->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Sous-secteur mis à jour avec succès',
            'data' => $sousSecteur
        ]);
    }

    /**
     * Supprime un sous-secteur
     */
    public function destroy(SousSecteur $sousSecteur): JsonResponse
    {
        if ($sousSecteur->activites()->exists() || $sousSecteur->produits()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer : des activités ou produits sont associés'
            ], 422);
        }

        $sousSecteur->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sous-secteur supprimé avec succès'
        ]);
    }

    /**
     * Récupère les produits d'un sous-secteur
     */
    public function produits(SousSecteur $sousSecteur): JsonResponse
    {
        $produits = $sousSecteur->produits()
            ->with(['secteur', 'activite', 'producteur'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $produits
        ]);
    }
}
