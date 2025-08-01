<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Obtenir les évaluations d'un produit
     */
    public function productReviews(Produit $product): JsonResponse
    {
        $reviews = $product->reviews()->with('user')->get();

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
        $this->authorize('create', Review::class);

        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $user = $request->user();

        // Vérifier que l'utilisateur a acheté ce produit
        $hasOrdered = Commande::where('customer_id', $user->id)
            ->whereHas('produits', function ($query) use ($request) {
                $query->where('produits.id', $request->product_id);
            })
            ->whereIn('status', [2, 3, 4]) // Commandes validées, expédiées ou livrées
            ->exists();

        if (!$hasOrdered) {
            return response()->json([
                'message' => 'Vous devez avoir acheté ce produit pour le noter'
            ], 403);
        }

        // Vérifier si l'utilisateur a déjà noté ce produit
        $existingReview = Review::where('user_id', $user->id)
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'Vous avez déjà noté ce produit.'
            ], 409); // 409 Conflict
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Évaluation ajoutée avec succès',
            'data' => $review
        ], 201);
    }

    /**
     * Mettre à jour une évaluation
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        $this->authorize('update', $review);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Évaluation mise à jour avec succès',
            'data' => $review
        ], 200);
    }

    /**
     * Supprimer une évaluation
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        $review->delete();

        return response()->json([
            'message' => 'Évaluation supprimée avec succès'
        ], 200);
    }
}
 