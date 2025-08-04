<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\Produit;
// use App\Models\Secteur;
// use App\Models\SousSecteur;
// use App\Models\Activite;
use App\Models\Categorie;
use App\Http\Resources\ProduitResource;
use App\Http\Resources\ProduitCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits avec filtres optionnels
     */
    public function index(Request $request): JsonResponse
    {
        $query = Produit::with(['categorie', 'producer', 'images']);

        // Appliquer les filtres si présents
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->has('producer_id')) {
            $query->where('producer_id', $request->producer_id);
        }

        $produits = $query->paginate(15);

        return response()->json([
            'message' => 'Liste des produits récupérée avec succès',
            'data' => new ProduitCollection($produits)
        ], 200);
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(StoreProduitRequest $request): JsonResponse
    {
        $this->authorize('create', Produit::class);

        $data = $request->validated();

        // S'assurer que le champ 'isbio' est bien pris en compte même s'il n'est pas envoyé
        if (!isset($data['isbio'])) {
            $data['isbio'] = true; // valeur par défaut comme dans la migration
        }

        $produit = Produit::create($data);

        // Gestion des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('produits', 'public');
                $produit->images()->create([
                    'name' => $image->getClientOriginalName(),
                    'type' => $image->getClientMimeType(),
                    'path' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Produit créé avec succès.',
            'data' => new ProduitResource($produit->load(['categorie', 'producer', 'images']))
        ], 201);
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Produit $produit): JsonResponse
{
    $this->authorize('view', $produit);

    $produit->load(['categorie', 'producer', 'images']);

    return response()->json([
        'message' => 'Produit récupéré avec succès',
        'data' => new ProduitResource($produit)  // ✅ Correct : tu passes par la resource
    ], 200);
}


    
}
