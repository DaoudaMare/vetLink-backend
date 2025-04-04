<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeRequest;
use App\Http\Requests\UpdateCommandeRequest;
use App\Models\Commande;
use Illuminate\Http\JsonResponse;

class CommandeController extends Controller
{
    /**
     * Afficher toutes les commandes.
     */
    public function index(): JsonResponse
    {
        $commandes = Commande::with('produits')->get();
        return response()->json($commandes);
    }

    /**
     * Créer une nouvelle commande avec StoreCommandeRequest.
     */
    public function store(StoreCommandeRequest $request): JsonResponse
    {
        $commande = Commande::create([
            'user_id' => $request->user_id,
            'statut' => 'en attente'
        ]);

        // Associer les produits avec les quantités (évite les doublons avec sync)
        $produits = collect($request->produits)->mapWithKeys(function ($produit) {
            return [$produit['id'] => ['quantite' => $produit['quantite']]];
        });

        $commande->produits()->sync($produits);

        return response()->json([
            'message' => 'Commande créée avec succès',
            'commande' => $commande->load('produits')
        ], 201);
    }

    /**
     * Récupérer une commande spécifique.
     */
    public function show(Commande $commande): JsonResponse
    {
        return response()->json($commande->load('produits'));
    }

    /**
     * Mettre à jour une commande avec UpdateCommandeRequest.
     */
    public function update(UpdateCommandeRequest $request, Commande $commande): JsonResponse
    {
        // Mettre à jour le statut
        $commande->update(['statut' => $request->statut]);

        // Mise à jour des produits si fournis
        if ($request->has('produits')) {
            $produits = collect($request->produits)->mapWithKeys(function ($produit) {
                return [$produit['id'] => ['quantite' => $produit['quantite']]];
            });

            $commande->produits()->sync($produits);
        }

        return response()->json([
            'message' => 'Commande mise à jour avec succès',
            'commande' => $commande->load('produits')
        ]);
    }

    /**
     * Supprimer une commande.
     */
    public function destroy(Commande $commande): JsonResponse
    {
        $commande->delete();
        return response()->json(['message' => 'Commande supprimée avec succès']);
    }

    /**
     * Récupérer toutes les commandes d'un utilisateur spécifique.
     */
    public function getCommandesByUser($id): JsonResponse
    {
        $commandes = Commande::where('user_id', $id)->with('produits')->get();
        return response()->json($commandes);
    }

    /**
     * Valider une commande et mettre à jour les ventes des produits.
     */
    public function validerCommande($commande_id): JsonResponse
    {
        $commande = Commande::findOrFail($commande_id);

        if ($commande->statut !== 'validée') {
            // Mise à jour des ventes pour chaque produit
            $commande->produits->each(function ($produit) {
                $produit->increment('ventes', $produit->pivot->quantite);
            });

            // Changement du statut
            $commande->update(['statut' => 'validée']);

            return response()->json([
                'message' => 'Commande validée et ventes mises à jour',
                'commande' => $commande->load('produits')
            ], 200);
        }

        return response()->json(['message' => 'Cette commande est déjà validée'], 400);
    }
}
