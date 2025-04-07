<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits.
     */
    public function index(): JsonResponse
    {
        $produits = Produit::all();
        return response()->json([
            'message' => 'Liste des produits récupérée avec succès',
            'produits' => $produits
        ], 200);
    }
    /**
     * Enregistrer un nouveau produit.
     */
    public function store(StoreProduitRequest $request): JsonResponse
    {
        $this->authorize('create', Produit::class);

        $data = $request->validated();


        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit = Produit::create($data);

        return response()->json([
            'message' => 'Produit créé avec succès.',
            'produit' => $produit
        ], 201);
    }

    /**
     * Afficher un produit spécifique.
     */
    public function show(Produit $produit): JsonResponse
    {
        $this->authorize('view', $produit);

        return response()->json([
            'message' => 'Produit récupéré avec succès',
            'produit' => $produit
        ], 200);
    }

    /**
     * Mettre à jour un produit.
     */
    public function update(UpdateProduitRequest $request, Produit $produit): JsonResponse
    {
        $this->authorize('update', $produit);

        $data = $request->validated();


        if ($request->hasFile('image')) {

            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }

            $data['image'] = $request->file('image')->store('produits', 'public');
        }

        $produit->update($data);

        return response()->json([
            'message' => 'Produit mis à jour avec succès.',
            'produit' => $produit
        ], 200);
    }

    /**
     * Supprimer un produit.
     */
    public function destroy(Produit $produit): JsonResponse
    {
        $this->authorize('delete', $produit);


        if ($produit->image) {
            Storage::disk('public')->delete($produit->image);
        }

        $produit->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès.'
        ], 200);
    }
}
