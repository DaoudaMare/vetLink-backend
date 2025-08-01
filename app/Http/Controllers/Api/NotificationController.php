<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return response()->json([
            'message' => 'Notifications récupérées avec succès',
            'data' => $notifications
        ], 200);
    }

    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|uuid|exists:notifications,id'
        ]);

        Auth::user()->notifications()->where('id', $request->notification_id)->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Notification marquée comme lue'
        ], 200);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Toutes les notifications marquées comme lues'
        ], 200);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|uuid|exists:notifications,id'
        ]);

        Auth::user()->notifications()->where('id', $request->notification_id)->delete();

        return response()->json([
            'message' => 'Notification supprimée'
        ], 200);
    }
} 