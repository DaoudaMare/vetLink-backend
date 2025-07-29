<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProduitResource;
use App\Http\Resources\CommandeResource;

class CustomerController extends Controller
{
    /**
     * Obtenir le profil du client connecté
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

       return response()->json([
    'message' => 'Profil client récupéré avec succès',
    'data' => [
        'id' => $user->id,
        'full_name' => $user->firstName . ' ' . $user->lastName,
        'email' => $user->email,
        'tel1' => $user->tel1,
        'tel2' => $user->tel2,
        'address' => $user->address,
        'user_type' => $user->userType->title ?? null,
        'profile_photo_url' => $user->photo_url,
        'created_at' => $user->created_at,
    ]
], 200);

    }

    /**
     * Rechercher et filtrer les produits
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = Produit::with(['categorie', 'producer', 'images'])
            ->where('quantity', '>', 0);

        // Filtre par catégorie
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par producteur
        if ($request->has('producer_id')) {
            $query->where('producer_id', $request->producer_id);
        }

        // Filtre par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtre bio
        if ($request->has('isbio')) {
            $query->where('isbio', $request->isbio);
        }

        // Recherche par nom
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(15);

        return response()->json([
            'message' => 'Produits récupérés avec succès',
            'data' => ProduitResource::collection($products)
        ], 200);
    }

    /**
     * Obtenir les détails d'un produit
     */
    public function productDetails(Produit $product): JsonResponse
    {
        return response()->json([
            'message' => 'Détails du produit récupérés avec succès',
            'data' => new ProduitResource($product->load(['categorie', 'producer', 'images']))
        ], 200);
    }

    /**
     * Passer une commande
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $product = Produit::findOrFail($request->product_id);

        // Vérifier la disponibilité
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'message' => 'Quantité insuffisante en stock',
                'available_quantity' => $product->quantity
            ], 422);
        }

        // Calculer le prix total
        $totalPrice = $product->price * $request->quantity;

        // Créer la commande
        $order = Commande::create([
            'num' => 'CMD-' . strtoupper(uniqid()),
            'customer_id' => $user->id,
            'product_id' => $product->id,
            'Quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 0, // En attente
            'delivery_status' => 0, // Non livré
            'payment' => 0 // Non payé
        ]);

        // Réduire la quantité du produit
        $product->update([
            'quantity' => $product->quantity - $request->quantity
        ]);

        return response()->json([
            'message' => 'Commande passée avec succès',
            'data' => new CommandeResource($order->load(['produit']))
        ], 201);
    }

    /**
     * Obtenir l'historique des commandes du client
     */
    public function orderHistory(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = Commande::where('customer_id', $user->id)
            ->with(['produit.producer', 'produit.categorie'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Historique des commandes récupéré avec succès',
            'data' => CommandeResource::collection($orders)
        ], 200);
    }

    /**
     * Obtenir les détails d'une commande
     */
    public function orderDetails(Request $request, Commande $order): JsonResponse
    {
        $user = $request->user();

        // Vérifier que la commande appartient au client
        if ($order->customer_id !== $user->id) {
            return response()->json([
                'message' => 'Accès non autorisé'
            ], 403);
        }

        return response()->json([
            'message' => 'Détails de la commande récupérés avec succès',
            'data' => new CommandeResource($order->load(['produit.producer', 'produit.categorie']))
        ], 200);
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(Request $request, Commande $order): JsonResponse
    {
        $user = $request->user();

        // Vérifier que la commande appartient au client
        if ($order->customer_id !== $user->id) {
            return response()->json([
                'message' => 'Accès non autorisé'
            ], 403);
        }

        // Vérifier que la commande peut être annulée
        if ($order->status > 1) {
            return response()->json([
                'message' => 'Cette commande ne peut plus être annulée'
            ], 422);
        }

        // Annuler la commande
        $order->update(['status' => 3]); // Annulée

        // Remettre la quantité en stock
        $product = $order->produit;
        $product->update([
            'quantity' => $product->quantity + $order->Quantity
        ]);

        return response()->json([
            'message' => 'Commande annulée avec succès'
        ], 200);
    }

    /**
     * Obtenir les catégories disponibles
     */
    public function categories(): JsonResponse
    {
        $categories = Categorie::all();

        return response()->json([
            'message' => 'Catégories récupérées avec succès',
            'data' => $categories
        ], 200);
    }

    /**
     * Produits recommandés
     */
    public function recommendedProducts(Request $request): JsonResponse
    {
        $user = $request->user();

        // Logique simple de recommandation basée sur les commandes précédentes
        $userCategories = Commande::where('customer_id', $user->id)
            ->join('produits', 'commandes.product_id', '=', 'produits.id')
            ->pluck('produits.categorie_id')
            ->unique();

        $recommendedProducts = Produit::with(['categorie', 'producer', 'images'])
            ->whereIn('categorie_id', $userCategories)
            ->where('quantity', '>', 0)
            ->where('producer_id', '!=', $user->id) // Exclure ses propres produits
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return response()->json([
            'message' => 'Produits recommandés récupérés avec succès',
            'data' => ProduitResource::collection($recommendedProducts)
        ], 200);
    }

/**
 * Obtenir les commandes passées aujourd'hui par le client
 */
public function todaysOrders(Request $request): JsonResponse
{
    $user = $request->user();

    $orders = Commande::where('customer_id', $user->id)
        ->whereDate('created_at', today())
        ->with(['produit.producer', 'produit.categorie'])
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'message' => 'Commandes du jour récupérées avec succès',
        'count' => $orders->count(),
        'data' => CommandeResource::collection($orders)
    ], 200);
}

/**
 * Obtenir les commandes en cours (non terminées) du client
 */
public function currentOrders(Request $request): JsonResponse
{
    $user = $request->user();

    $orders = Commande::where('customer_id', $user->id)
        ->whereNotIn('status', [3, 4]) // Exclure Annulé (3) et Terminé (4)
        ->with(['produit.producer', 'produit.categorie'])
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'message' => 'Commandes en cours récupérées avec succès',
        'count' => $orders->count(),
        'data' => CommandeResource::collection($orders)
    ], 200);
}

}
