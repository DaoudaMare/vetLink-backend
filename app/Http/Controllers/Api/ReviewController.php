<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Obtenir les évaluations d'un produit
     */
    public function productReviews(Produit $product): JsonResponse
    {
        try {
            $reviews = $product->reviews()->with('user')->latest()->get();
            
            // Calculer la moyenne des notes
            $averageRating = $reviews->avg('rating');
            $totalReviews = $reviews->count();

            return response()->json([
                'message' => 'Évaluations récupérées avec succès',
                'data' => [
                    'reviews' => ReviewResource::collection($reviews),
                    'statistics' => [
                        'average_rating' => round($averageRating, 1),
                        'total_reviews' => $totalReviews,
                        'rating_distribution' => [
                            '5' => $reviews->where('rating', 5)->count(),
                            '4' => $reviews->where('rating', 4)->count(),
                            '3' => $reviews->where('rating', 3)->count(),
                            '2' => $reviews->where('rating', 2)->count(),
                            '1' => $reviews->where('rating', 1)->count(),
                        ]
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des évaluations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter une évaluation
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Review::class);

        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);

        $user = $request->user();

        // Vérifier que l'utilisateur a acheté ce produit
        $hasOrdered = Commande::where('customer_id', $user->id)
            ->whereHas('produits', function ($query) use ($request) {
                $query->where('produits.id', $request->produit_id);
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
                                ->where('produit_id', $request->produit_id)
                                ->first();

        if ($existingReview) {
            return response()->json([
                'message' => 'Vous avez déjà noté ce produit.'
            ], 409); // 409 Conflict
        }

        try {
            $review = Review::create([
                'user_id' => $user->id,
                'produit_id' => $request->produit_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'message' => 'Évaluation ajoutée avec succès',
                'data' => new ReviewResource($review->load('user'))
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'ajout de l\'évaluation',
                'error' => $e->getMessage()
            ], 500);
        }
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

        try {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'message' => 'Évaluation mise à jour avec succès',
                'data' => new ReviewResource($review->load('user'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'évaluation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une évaluation
     */
    public function destroy(Review $review): JsonResponse
    {
        $this->authorize('delete', $review);

        try {
            $review->delete();

            return response()->json([
                'message' => 'Évaluation supprimée avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'évaluation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
 