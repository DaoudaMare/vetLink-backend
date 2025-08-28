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
use App\Models\ProductImage;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;

class ProducerController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Obtenir le profil du producteur connecté
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isProducer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        return response()->json([
         'message' => 'Profil producteur récupéré avec succès',
        'data' => [
        'id' => $user->id,
        'full_name' => $user->firstName . ' ' . $user->lastName,
        'email' => $user->email,
        'tel1' => $user->tel1,
        'tel2' => $user->tel2,
        'address' => $user->address,
        'user_type' => $user->userType->title ?? null,
        'organization' => $user->organization->name ?? null,
        'created_at' => $user->created_at,
    ]
], 200);

    }

    /**
     * Obtenir tous les produits du producteur connecté
     */
    public function myProducts(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isProducer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

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
        $this->authorize('create', Produit::class);

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
            'data' => new ProduitResource($product->load(['categorie', 'images', 'producer']))
        ], 201);
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduct(Request $request, Produit $product): JsonResponse
    {
        $this->authorize('update', $product);

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
        $this->authorize('delete', $product);

        // Supprimer les images associées au produit du stockage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete(); // Supprimer l'enregistrement de l'image de la base de données
        }

        $product->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès'
        ], 200);
    }

    public function addProductImages(Request $request, Produit $product): JsonResponse
    {
        $this->authorize('update', $product);

        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('produits', 'public');
                $productImage = $product->images()->create([
                    'name' => $image->getClientOriginalName(),
                    'type' => $image->getClientMimeType(),
                    'path' => $path
                ]);
                $uploadedImages[] = $productImage;
            }
        }

        return response()->json([
            'message' => 'Images ajoutées avec succès',
            'data'    => $uploadedImages
        ], 201);
    }

    public function deleteProductImage(Request $request, Produit $product, ProductImage $image): JsonResponse
    {
        $this->authorize('update', $product);

        // Optional: Check if the image belongs to the product for extra security
        if ($image->product_id !== $product->id) {
            return response()->json(['message' => 'Image not found for this product.'], 404);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json([
            'message' => 'Image supprimée avec succès'
        ], 200);
    }


    /**
     * Obtenir les commandes reçues par le producteur
     */
    public function myOrders(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isProducer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $orders = Commande::whereHas('produits', function($query) use ($user) {
            $query->where('producer_id', $user->id);
        })
        ->with(['customer', 'produits'])
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
        if (!$user->isProducer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $totalProducts = Produit::where('producer_id', $user->id)->count();
        $totalOrders = Commande::whereHas('produits', function($query) use ($user) {
            $query->where('producer_id', $user->id);
        })->count();

        $totalRevenue = Commande::whereHas('produits', function($query) use ($user) {
            $query->where('producer_id', $user->id);
        })->where('payment', 1)->sum('total_price');

        return response()->json([
            'message' => 'Statistiques récupérées avec succès',
            'data' => [
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'pending_orders' => Commande::whereHas('produits', function($query) use ($user) {
                    $query->where('producer_id', $user->id);
                })->where('status', 0)->count()
            ]
        ], 200);
    }

    public function showOrder(Commande $order): JsonResponse
    {
        $this->authorize('view', $order);

        return response()->json([
            'message' => 'Détails de la commande récupérés avec succès',
            'data' => new CommandeResource($order->load(['customer', 'produits']))
        ], 200);
    }

    public function updateOrderStatus(Request $request, Commande $order): JsonResponse
    {
        $this->authorize('updateStatus', $order);

        $request->validate([
            'status' => 'required|integer|in:0,1,2,3,4', // 0: En attente, 1: Confirmé, 2: En préparation, 3: Expédié, 4: Livré
        ]);

        $user = $request->user(); // Get the authenticated producer

        // Get the IDs of products in this order that belong to the current producer
        $producerProductIdsInOrder = $order->produits()
                                       ->where('producer_id', $user->id)
                                       ->pluck('produits.id')
                                       ->toArray();

        // Update the status on the pivot table for these specific products
        $order->produits()->updateExistingPivot($producerProductIdsInOrder, ['status' => $request->status]);

        // Recalculate and update the main order status based on product statuses
        $order->recalculateOverallStatus();

        // Note: The notification currently sends the overall order status.
        // This might need to be refined to reflect product-specific status updates,
        // or a new notification system for individual product status changes.
        // For now, we'll keep the existing notification as is, but be aware of this.
        $this->notificationService->createNotification(
            $order->customer,
            'order_status_updated',
            [
                'order_num' => $order->num,
                'status' => $order->status, // CHANGED TO $order->status (calculated overall status)
            ]
        );

        // Reload the order to get the updated pivot data for the response
        $order->load('produits');

        return response()->json([
            'message' => 'Statut des produits du producteur mis à jour avec succès dans la commande',
            'data' => new CommandeResource($order)
        ], 200);
    }
}
