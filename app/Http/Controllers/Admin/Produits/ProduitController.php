<?php

namespace App\Http\Controllers\Admin\Produits;

use App\Http\Controllers\Controller;

use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits.
     */
    public function index()
    {
        $produits = Produit::all();
        return response()->json($produits);
    }

    /**
     * Créer un nouveau produit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'producer_id' => 'required|exists:users,id',
            'quantity' => 'required|numeric',
            'price' => 'required|integer',
            'measure' => 'nullable|in:kg,g,L,unité',
        ]);

        $producer = User::where('id', $request->producer_id)
                        ->whereIn('user_type', ['agriculteur', 'éleveur', 'pêcheur'])
                        ->first();

        if (!$producer) {
            return response()->json(['error' => 'Producer not valid.'], 422);
        }

        $produit = Produit::create($request->all());
        return response()->json($produit, 201);
    }

    /**
     * Afficher un produit spécifique.
     */
    public function show(Produit $produit)
    {
        return response()->json($produit->load(['categorie', 'producer', 'images']));
    }

    /**
     * Mettre à jour un produit existant.
     */
    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'categorie_id' => 'sometimes|exists:categories,id',
            'producer_id' => 'sometimes|exists:users,id',
            'quantity' => 'sometimes|numeric',
            'price' => 'sometimes|integer',
            'measure' => 'nullable|in:kg,g,L,unité',
        ]);

        if ($request->has('producer_id')) {
            $producer = User::where('id', $request->producer_id)
                            ->whereIn('user_type', ['agriculteur', 'éleveur', 'pêcheur'])
                            ->first();

            if (!$producer) {
                return response()->json(['error' => 'Producer not valid.'], 422);
            }
        }

        $produit->update($request->all());
        return response()->json($produit);
    }

    /**
     * Supprimer un produit.
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();
        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}
