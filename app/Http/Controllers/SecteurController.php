<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSecteurRequest;
use App\Http\Requests\UpdateSecteurRequest;
use App\Models\Activite;
use App\Models\Produit;
use App\Models\Secteur;
use App\Models\SousSecteur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecteurController extends Controller
{
    /**
     * Récupère tous les secteurs avec leurs sous-secteurs et activités
     */
    public function index(): JsonResponse
    {
        $secteurs = Secteur::with(['sousSecteurs.activites'])
            ->orderBy('nom')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $secteurs
        ]);
    }

    /**
     * Crée un nouveau secteur
     */
    public function store(StoreSecteurRequest $request): JsonResponse
    {
        $secteur = Secteur::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Secteur créé avec succès',
            'data' => $secteur
        ], 201);
    }

    /**
     * Récupère un secteur spécifique avec ses relations
     */
    public function show(Secteur $secteur): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $secteur->load(['sousSecteurs.activites'])
        ]);
    }

    /**
     * Met à jour un secteur
     */
    public function update(UpdateSecteurRequest $request, Secteur $secteur): JsonResponse
    {
        $secteur->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Secteur mis à jour avec succès',
            'data' => $secteur
        ]);
    }

    /**
     * Supprime un secteur
     */
    public function destroy(Secteur $secteur): JsonResponse
    {
        if ($secteur->sousSecteurs()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer : des sous-secteurs sont associés'
            ], 422);
        }

        $secteur->delete();

        return response()->json([
            'success' => true,
            'message' => 'Secteur supprimé avec succès'
        ]);
    }

    /**
     * Récupère les produits d'un secteur
     */
    public function produits(Secteur $secteur): JsonResponse
    {
        $produits = $secteur->produits()
            ->with(['sousSecteur', 'activite', 'producteur'])
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $produits->items(),
            'meta' => [
                'current_page' => $produits->currentPage(),
                'total' => $produits->total()
            ]
        ]);
    }

    /**
     * Statistiques par secteur
     */
    public function stats(Secteur $secteur): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'nombre_produits' => $secteur->produits()->count(),
                'nombre_sous_secteurs' => $secteur->sousSecteurs()->count(),
                'produits_par_sous_secteur' => $secteur->sousSecteurs()->withCount('produits')->get()
            ]
        ]);
    }

    /**
     * Récupère tous les sous-secteurs
     */
    public function sousSecteurs(): JsonResponse
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
     * Récupère un sous-secteur spécifique
     */
    public function sousSecteurDetail(SousSecteur $sousSecteur): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $sousSecteur->load(['secteur', 'activites'])
        ]);
    }

    /**
     * Récupère toutes les activités
     */
    public function activites(): JsonResponse
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
     * Récupère une activité spécifique
     */
    public function activiteDetail(Activite $activite): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $activite->load(['sousSecteur.secteur'])
        ]);
    }

    /**
     * Recherche avancée dans les produits
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'secteur' => 'sometimes|string|exists:secteurs,code',
            'sous_secteur' => 'sometimes|string|exists:sous_secteurs,code',
            'page' => 'sometimes|integer|min:1'
        ]);

        $query = Produit::query()
            ->with(['secteur', 'sousSecteur', 'activite', 'producteur']);

        if ($request->has('secteur')) {
            $query->whereHas('secteur', fn($q) => $q->where('code', $request->secteur));
        }

        if ($request->has('sous_secteur')) {
            $query->whereHas('sousSecteur', fn($q) => $q->where('code', $request->sous_secteur));
        }

        $resultats = $query->paginate($request->page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $resultats->items(),
            'meta' => [
                'current_page' => $resultats->currentPage(),
                'per_page' => $resultats->perPage(),
                'total' => $resultats->total()
            ]
        ]);
    }
}