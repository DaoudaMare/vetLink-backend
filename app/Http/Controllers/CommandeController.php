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
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes avec filtres optionnels
     */
    public function index(Request $request): JsonResponse
    {
        $query = Commande::with(['customer', 'produits']);

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
     * Enregistrer une nouvelle commande multi-produits
     */
    public function store(StoreCommandeRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        try {
            DB::beginTransaction();
            
            // Générer un numéro unique pour la commande
            $commandeData = [
                'num' => 'CMD-' . strtoupper(Str::random(8)),
                'customer_id' => $data['customer_id'],
                'status' => 0, // En attente
                'delivery_status' => 0, // Non livré
                'payment' => 0, // Non payé
                'total_price' => 0 // Sera calculé
            ];
            
            // Créer la commande
            $commande = Commande::create($commandeData);
            
            $totalPrice = 0;
            
            // Traiter chaque produit
            foreach ($data['produits'] as $produitData) {
                $produit = Produit::findOrFail($produitData['product_id']);
                
                // Vérifier la disponibilité
                if ($produit->quantity < $produitData['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Quantité insuffisante pour le produit '{$produit->name}'",
                        'product_name' => $produit->name,
                        'available_quantity' => $produit->quantity,
                        'requested_quantity' => $produitData['quantity']
                    ], 422);
                }
                
                // Attacher le produit à la commande
                $commande->produits()->attach($produit->id, [
                    'quantity' => $produitData['quantity']
                ]);
                
                // Réduire le stock
                $produit->decrement('quantity', $produitData['quantity']);
                
                // Calculer le prix total
                $totalPrice += $produit->price * $produitData['quantity'];
            }
            
            // Mettre à jour le prix total
            $commande->update(['total_price' => $totalPrice]);
            
            DB::commit();
            
            return response()->json([
                'message' => 'Commande créée avec succès',
                'data' => new CommandeResource($commande->load(['customer', 'produits']))
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la création de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Commande $commande): JsonResponse
    {
        return response()->json([
            'message' => 'Commande récupérée avec succès',
            'data' => new CommandeResource($commande->load(['customer', 'produits']))
        ], 200);
    }

    /**
     * Mettre à jour une commande (statuts uniquement)
     */
    public function update(UpdateCommandeRequest $request, Commande $commande): JsonResponse
    {
        $data = $request->validated();
        
        try {
            // Seuls les statuts peuvent être modifiés après création
            $allowedFields = ['status', 'delivery_status', 'payment'];
            $updateData = array_intersect_key($data, array_flip($allowedFields));
            
            $commande->update($updateData);

            return response()->json([
                'message' => 'Commande mise à jour avec succès',
                'data' => new CommandeResource($commande->load(['customer', 'produits']))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annuler une commande (remet en stock)
     */
    public function destroy(Commande $commande): JsonResponse
    {
        try {
            DB::beginTransaction();
            
            // Remettre en stock tous les produits de la commande
            foreach ($commande->produits as $produit) {
                $quantity = $produit->pivot->quantity;
                $produit->increment('quantity', $quantity);
            }
            
            // Détacher tous les produits
            $commande->produits()->detach();
            
            // Supprimer la commande
            $commande->delete();
            
            DB::commit();

            return response()->json([
                'message' => 'Commande annulée avec succès'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de l\'annulation de la commande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour le statut de livraison
     */
    public function updateDeliveryStatus(Request $request, Commande $commande): JsonResponse
    {
        $request->validate([
            'delivery_status' => 'required|integer|min:0|max:3'
        ]);

        $commande->update([
            'delivery_status' => $request->delivery_status
        ]);

        return response()->json([
            'message' => 'Statut de livraison mis à jour avec succès',
            'data' => new CommandeResource($commande->load(['customer', 'produits']))
        ], 200);
    }

    /**
     * Mettre à jour le statut de paiement
     */
    public function updatePaymentStatus(Request $request, Commande $commande): JsonResponse
    {
        $request->validate([
            'payment' => 'required|integer|min:0|max:1'
        ]);

        $commande->update([
            'payment' => $request->payment
        ]);

        return response()->json([
            'message' => 'Statut de paiement mis à jour avec succès',
            'data' => new CommandeResource($commande->load(['customer', 'produits']))
        ], 200);
    }

    /**
     * Obtenir les commandes d'un client spécifique
     */
    public function customerOrders($customerId): JsonResponse
    {
        $commandes = Commande::with(['produits'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Commandes du client récupérées avec succès',
            'data' => CommandeResource::collection($commandes)
        ], 200);
    }
}
