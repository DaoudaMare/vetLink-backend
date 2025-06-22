<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * Obtenir les évaluations d'un produit
     */
    public function productReviews(Produit $product): JsonResponse
    {
        // Simuler des évaluations (à remplacer par un vrai système)
        $reviews = [
            [
                'id' => 1,
                'user_name' => 'Jean Dupont',
                'rating' => 5,
                'comment' => 'Excellent produit, très frais !',
                'created_at' => now()->subDays(2)
            ],
            [
                'id' => 2,
                'user_name' => 'Marie Martin',
                'rating' => 4,
                'comment' => 'Très bon produit, je recommande',
                'created_at' => now()->subDays(5)
            ]
        ];

        return response()->json([
            'message' => 'Évaluations récupérées avec succès',
            'data' => $reviews
        ], 200);
    }

    /**
     * Ajouter une évaluation
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $user = $request->user();

        // Vérifier que l'utilisateur a acheté ce produit
        $hasOrdered = Commande::where('customer_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('status', '>=', 2) // Commandes validées ou livrées
            ->exists();

        if (!$hasOrdered) {
            return response()->json([
                'message' => 'Vous devez avoir acheté ce produit pour le noter'
            ], 403);
        }

        // Logique pour sauvegarder l'évaluation
        return response()->json([
            'message' => 'Évaluation ajoutée avec succès'
        ], 201);
    }

    /**
     * Mettre à jour une évaluation
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'review_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        // Logique pour mettre à jour
        return response()->json([
            'message' => 'Évaluation mise à jour avec succès'
        ], 200);
    }

    /**
     * Supprimer une évaluation
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'review_id' => 'required|integer'
        ]);

        // Logique pour supprimer
        return response()->json([
            'message' => 'Évaluation supprimée avec succès'
        ], 200);
    }
} 