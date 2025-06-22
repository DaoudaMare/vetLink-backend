<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Obtenir les notifications de l'utilisateur
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Simuler des notifications (à remplacer par un vrai système de notifications)
        $notifications = [
            [
                'id' => 1,
                'type' => 'order_status',
                'title' => 'Statut de commande mis à jour',
                'message' => 'Votre commande #CMD-12345678 a été confirmée',
                'read' => false,
                'created_at' => now()->subHours(2)
            ],
            [
                'id' => 2,
                'type' => 'new_order',
                'title' => 'Nouvelle commande reçue',
                'message' => 'Vous avez reçu une nouvelle commande pour Pommes Bio',
                'read' => true,
                'created_at' => now()->subDays(1)
            ]
        ];

        return response()->json([
            'message' => 'Notifications récupérées avec succès',
            'data' => $notifications
        ], 200);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|integer'
        ]);

        // Logique pour marquer comme lue
        return response()->json([
            'message' => 'Notification marquée comme lue'
        ], 200);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        // Logique pour marquer toutes comme lues
        return response()->json([
            'message' => 'Toutes les notifications marquées comme lues'
        ], 200);
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|integer'
        ]);

        // Logique pour supprimer
        return response()->json([
            'message' => 'Notification supprimée'
        ], 200);
    }
} 