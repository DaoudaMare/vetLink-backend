<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProduitResource;
use App\Http\Resources\CommandeResource;
use App\Models\Status;
use App\Services\NotificationService;

class CustomerController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Obtenir le profil du client connecté
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isCustomer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

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
],
 200);

    }

    /**
     * Rechercher et filtrer les produits
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $query = Produit::with(['categorie', 'producer', 'images'])
            ->where('quantity', '>', 0);

        // Recherche par nom ou description
        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        // Filtre par catégorie
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtre par bio
        if ($request->has('isbio')) {
            $query->where('isbio', filter_var($request->isbio, FILTER_VALIDATE_BOOLEAN));
        }

        // Tri
        if ($request->has('sort_by')) {
            $sortOrder = $request->input('sort_order', 'asc');
            $query->orderBy($request->sort_by, $sortOrder);
        }

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
        $this->authorize('create', Commande::class);

        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:produits,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $totalPrice = 0;
        $productsToUpdate = [];

        foreach ($validated['products'] as $item) {
            $product = Produit::find($item['product_id']);

            if ($product->quantity < $item['quantity']) {
                return response()->json([
                    'message' => 'Quantité insuffisante en stock pour le produit: ' . $product->name,
                    'available_quantity' => $product->quantity
                ], 422);
            }

            $totalPrice += $product->price * $item['quantity'];
            $productsToUpdate[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
        }

        $commande = DB::transaction(function () use ($validated, $totalPrice, $productsToUpdate) {
            $commande = Commande::create([
                'num' => 'CMD-' . strtoupper(uniqid()),
                'customer_id' => Auth::id(),
                'total_price' => $totalPrice,
                'status' => 1, // Assuming 1 is 'pending'
                'delivery_status' => 1,
                'payment' => 0, // Not paid
            ]);

            foreach ($validated['products'] as $item) {
                $commande->produits()->attach($item['product_id'], ['quantity' => $item['quantity']]);
            }

            foreach ($productsToUpdate as $data) {
                $data['product']->decrement('quantity', $data['quantity']);
            }

            return $commande;
        });

        return response()->json([
            'message' => 'Commande créée avec succès',
            'data' => new CommandeResource($commande->load('produits'))
        ], 201);
    }

    /**
     * Obtenir l'historique des commandes du client
     */
    public function orderHistory(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isCustomer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        $orders = $this->getUserOrdersQuery($request->user())->paginate(15);

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
        $this->authorize('view', $order);
        return response()->json([
            'message' => 'Détails de la commande récupérés avec succès',
            'data' => new CommandeResource($order->load(['produits.producer', 'produits.categorie']))
        ], 200);
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(Request $request, Commande $order): JsonResponse
    {
        $this->authorize('cancel', $order);

        $cancelledStatus = Status::where('name', 'Annulé')->first();
        $shippedStatus = Status::where('name', 'Expédié')->first(); // Exemple
        $deliveredStatus = Status::where('name', 'Livré')->first(); // Exemple

        if (!$cancelledStatus) {
            return response()->json(['message' => 'Le statut "Annulé" n\'est pas configuré.'], 500);
        }

        if ($order->status === $cancelledStatus->id) {
            return response()->json(['message' => 'Cette commande est déjà annulée.'], 409);
        }

        // Empêcher l\'annulation si la commande est déjà expédiée ou livrée
        if ($order->delivery_status >= $shippedStatus->id) { // Supposant que les statuts ont un ordre logique
            return response()->json(['message' => 'Impossible d\'annuler une commande qui est déjà expédiée ou livrée.'], 409);
        }

        DB::transaction(function () use ($order, $cancelledStatus) {
            foreach ($order->produits as $product) {
                $product->increment('quantity', $product->pivot->quantity);
            }

            $order->update(['status' => $cancelledStatus->id]);

            foreach ($order->produits as $product) {
                $this->notificationService->createNotification(
                    $product->producer,
                    'order_cancelled',
                    [
                        'order_num' => $order->num,
                        'product_name' => $product->name,
                    ]
                );
            }
        });

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
            'message' => 'Liste des catégories récupérée avec succès',
            'data' => $categories
        ], 200);
    }

    /**
     * Produits recommandés
     */
    public function recommendedProducts(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isCustomer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $popularProducts = Produit::withCount('commandes')
            ->orderBy('commandes_count', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Produits recommandés récupérés avec succès',
            'data' => ProduitResource::collection($popularProducts)
        ], 200);
    }

    /**
     * Obtenir les commandes passées aujourd'hui par le client
     */
    public function todaysOrders(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user->isCustomer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        $orders = $this->getUserOrdersQuery($request->user())
            ->whereBetween('created_at', [\Carbon\Carbon::today()->startOfDay(), \Carbon\Carbon::today()->endOfDay()])
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
        if (!$user->isCustomer()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }
        $excludedStatuses = Status::whereIn('name', ['Annulé', 'Terminé'])->pluck('id');
        $orders = $this->getUserOrdersQuery($request->user())
            ->whereNotIn('status', $excludedStatuses)
            ->get();

        return response()->json([
            'message' => 'Commandes en cours récupérées avec succès',
            'count' => $orders->count(),
            'data' => CommandeResource::collection($orders)
        ], 200);
    }

    /**
     * Requête de base pour obtenir les commandes d'un utilisateur
     */
    private function getUserOrdersQuery(User $user)
    {
        return Commande::where('customer_id', $user->id)
            ->with(['produits.producer', 'produits.categorie'])
            ->latest(); // latest() est un raccourci pour orderBy('created_at', 'desc')
    }
}