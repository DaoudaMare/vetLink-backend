<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProduitResource;
use App\Http\Resources\CommandeResource;

class ProducerController extends Controller
{
    /**
     * Obtenir le profil du producteur connecté
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'message' => 'Profil producteur récupéré avec succès',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'business_sector' => $user->businessSector,
                'user_type' => $user->user_type,
                'created_at' => $user->created_at
            ]
        ], 200);
    }

    /**
     * Obtenir tous les produits du producteur connecté
     */
    public function myProducts(Request $request): JsonResponse
    {
        $user = $request->user();
        $products = Produit::where('producer_id', $user->id)
            ->with(['categorie', 'images'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Produits du producteur récupérés avec succès',
            'data' => ProduitResource::collection($products)
        ], 200);
    }

    /**
     * Créer un nouveau produit
     */
    public function createProduct(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'measure' => 'required|in:kg,g,L,unité',
            'isbio' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = $request->user();
        
        $product = Produit::create([
            'name' => $request->name,
            'description' => $request->description,
            'categorie_id' => $request->categorie_id,
            'producer_id' => $user->id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'measure' => $request->measure,
            'isbio' => $request->isbio ?? true
        ]);

        // Gestion des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('produits', 'public');
                $product->images()->create([
                    'name' => $image->getClientOriginalName(),
                    'type' => $image->getClientMimeType(),
                    'path' => $path
                ]);
            }
        }

        return response()->json([
            'message' => 'Produit créé avec succès',
            'data' => new ProduitResource($product->load(['categorie', 'images']))
        ], 201);
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduct(Request $request, Produit $product): JsonResponse
    {
        $user = $request->user();
        
        // Vérifier que le produit appartient au producteur
        if ($product->producer_id !== $user->id) {
            return response()->json([
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'sometimes|exists:categories,id',
            'quantity' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
            'measure' => 'sometimes|in:kg,g,L,unité',
            'isbio' => 'boolean'
        ]);

        $product->update($request->only([
            'name', 'description', 'categorie_id', 'quantity', 'price', 'measure', 'isbio'
        ]));

        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'data' => new ProduitResource($product->load(['categorie', 'images']))
        ], 200);
    }

    /**
     * Supprimer un produit
     */
    public function deleteProduct(Request $request, Produit $product): JsonResponse
    {
        $user = $request->user();
        
        // Vérifier que le produit appartient au producteur
        if ($product->producer_id !== $user->id) {
            return response()->json([
                'message' => 'Accès non autorisé'
            ], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ], 200);
    }

    /**
     * Obtenir les commandes reçues par le producteur
     */
    public function myOrders(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $orders = Commande::whereHas('produit', function($query) use ($user) {
            $query->where('producer_id', $user->id);
        })
        ->with(['customer', 'produit'])
        ->latest()
        ->paginate(15);

        return response()->json([
            'message' => 'Commandes récupérées avec succès',
            'data' => CommandeResource::collection($orders)
        ], 200);
    }

    /**
     * Statistiques du producteur
     */
    public function statistics(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $totalProducts = Produit::where('producer_id', $user->id)->count();
        $totalOrders = Commande::whereHas('produit', function($query) use ($user) {
            $query->where('producer_id', $user->id);
        })->count();
        
        $totalRevenue = Commande::whereHas('produit', function($query) use ($user) {
            $query->where('producer_id', $user->id);
        })->where('payment', 1)->sum('total_price');

        return response()->json([
            'message' => 'Statistiques récupérées avec succès',
            'data' => [
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'pending_orders' => Commande::whereHas('produit', function($query) use ($user) {
                    $query->where('producer_id', $user->id);
                })->where('status', 0)->count()
            ]
        ], 200);
    }
} 