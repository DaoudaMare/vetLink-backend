<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Http\Requests\StoreCommandeRequest;
use App\Http\Requests\UpdateCommandeRequest;
use App\Http\Resources\CommandeResource;
use App\Http\Resources\CommandeCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes avec filtres optionnels
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Commande::class);

        $query = Commande::with(['customer', 'produit']);

        // Filtres
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }

        $commandes = $query->latest()->paginate(15);

        return response()->json([
            'message' => 'Liste des commandes récupérée avec succès',
            'data' => new CommandeCollection($commandes)
        ], 200);
    }

    /**
     * Enregistrer une nouvelle commande
     */
    public function store(StoreCommandeRequest $request): JsonResponse
    {
        $this->authorize('create', Commande::class);

        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            
            // Générer un numéro unique pour la commande
            $data['num'] = 'CMD-' . strtoupper(Str::random(8));
            
            // Récupérer le produit et vérifier la disponibilité
            $produit = Produit::findOrFail($data['product_id']);
            
            // Vérifier si la quantité demandée est disponible
            if ($produit->quantity < $data['Quantity']) {
                return response()->json([
                    'message' => 'Quantité insuffisante en stock',
                    'available_quantity' => $produit->quantity
                ], 422);
            }
            
            // Calculer le prix total
            $data['total_price'] = $produit->price * $data['Quantity'];
            
            // Définir les statuts par défaut en utilisant les noms de la table statuses
            $data['status'] = Status::where('name', 'En attente')->first()->id; 
            $data['delivery_status'] = Status::where('name', 'En attente')->first()->id; // Supposons que 'En attente' est aussi le statut initial de livraison
            $data['payment'] = 0; // Non payé (si 0/1 est le standard pour le paiement)

            // Créer la commande
            $commande = Commande::create($data);

            // Réduire la quantité du produit
            $produit->update([
                'quantity' => $produit->quantity - $data['Quantity']
            ]);

            return response()->json([
                'message' => 'Commande créée avec succès',
                'data' => new CommandeResource($commande->load(['customer', 'produit']))
            ], 201);
        });
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Commande $commande): JsonResponse
    {
        $this->authorize('view', $commande);

        return response()->json([
            'message' => 'Commande récupérée avec succès',
            'data' => new CommandeResource($commande->load(['customer', 'produit']))
        ], 200);
    }

    /**
     * Mettre à jour une commande
     */
    public function update(UpdateCommandeRequest $request, Commande $commande): JsonResponse
    {
        $this->authorize('update', $commande);

        return DB::transaction(function () use ($request, $commande) {
            $data = $request->validated();

            // Si la quantité est modifiée
            if (isset($data['Quantity'])) {
                $produit = Produit::findOrFail($commande->product_id);
                
                // Calculer la différence de quantité
                $quantityDifference = $data['Quantity'] - $commande->Quantity;
                
                // Vérifier si la nouvelle quantité est disponible
                if ($quantityDifference > 0 && $produit->quantity < $quantityDifference) {
                    return response()->json([
                        'message' => 'Quantité insuffisante en stock',
                        'available_quantity' => $produit->quantity
                    ], 422);
                }
                
                // Mettre à jour la quantité du produit
                $produit->update([
                    'quantity' => $produit->quantity - $quantityDifference
                ]);
                
                // Recalculer le prix total
                $data['total_price'] = $produit->price * $data['Quantity'];
            }

            $commande->update($data);

            return response()->json([
                'message' => 'Commande mise à jour avec succès',
                'data' => new CommandeResource($commande->load(['customer', 'produit']))
            ], 200);
        });
    }

    /**
     * Supprimer une commande
     */
    public function destroy(Commande $commande): JsonResponse
    {
        $this->authorize('delete', $commande);

        return DB::transaction(function () use ($commande) {
            // Récupérer le produit associé à la commande
            $produit = Produit::findOrFail($commande->product_id);
            
            // Remettre la quantité en stock
            $produit->update([
                'quantity' => $produit->quantity + $commande->Quantity
            ]);

            $commande->delete();

            return response()->json([
                'message' => 'Commande supprimée avec succès'
            ], 200);
        });
    }

    /**
     * Mettre à jour le statut de livraison
     */
    public function updateDeliveryStatus(Request $request, Commande $commande): JsonResponse
    {
        $this->authorize('updateStatus', $commande);

        $request->validate([
            'delivery_status' => 'required|integer|exists:statuses,id' // Valider que l'ID existe dans la table statuses
        ]);

        $commande->update([
            'delivery_status' => $request->delivery_status
        ]);

        return response()->json([
            'message' => 'Statut de livraison mis à jour avec succès',
            'data' => new CommandeResource($commande->load(['customer', 'produit']))
        ], 200);
    }

    /**
     * Mettre à jour le statut de paiement
     */
    public function updatePaymentStatus(Request $request, Commande $commande): JsonResponse
    {
        $this->authorize('updateStatus', $commande);

        $request->validate([
            'payment' => 'required|integer|in:0,1' // 0: Non payé, 1: Payé
        ]);

        $commande->update([
            'payment' => $request->payment
        ]);

        return response()->json([
            'message' => 'Statut de paiement mis à jour avec succès',
            'data' => new CommandeResource($commande->load(['customer', 'produit']))
        ], 200);
    }

    /**
     * Obtenir les commandes d'un client spécifique
     */
    public function customerOrders(Request $request, $customerId): JsonResponse
    {
        // Seuls les administrateurs peuvent voir les commandes d'un autre client
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $perPage = $request->query('per_page', 15);
        $commandes = Commande::with(['produit'])
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'message' => 'Commandes du client récupérées avec succès',
            'data' => CommandeResource::collection($commandes)
        ], 200);
    }
}
