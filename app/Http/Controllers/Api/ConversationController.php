<?php


// app/Http/Controllers/API/ConversationController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();

    $conversations = Conversation::where(function($q) use ($user) {
            $q->where('user_one_id', $user->id)
              ->orWhere('user_two_id', $user->id);
        })
        // Filtre par produit si le paramètre est présent
        ->when($request->has('product_id'), function($query) use ($request) {
            $query->whereHas('messages', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        })
        // Chargement des relations
        ->with(['userOne', 'userTwo', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])
        ->get()
        // Calcul des messages non lus
        ->map(function($conv) use ($user) {
            $conv->unread_count = $conv->messages()
                ->where('sender_id', '!=', $user->id)
                ->where('is_read', false)
                ->count();
            return $conv;
        });

    return response()->json($conversations);
}

    public function store(StoreConversationRequest $request)
    {
        $userOne = Auth::id();
        $userTwo = $request->user_two_id;

        $exists = Conversation::where(function($q) use ($userOne, $userTwo) {
            $q->where('user_one_id', $userOne)->where('user_two_id', $userTwo);
        })->orWhere(function($q) use ($userOne, $userTwo) {
            $q->where('user_one_id', $userTwo)->where('user_two_id', $userOne);
        })->first();

        if ($exists) {
            return response()->json($exists, 200);
        }

        $conversation = Conversation::create([
            'user_one_id' => $userOne,
            'user_two_id' => $userTwo,
        ]);

        return response()->json($conversation, 201);
    }


    // ConversationController.php
public function destroy($id)
{
    $conversation = Conversation::findOrFail($id);

    // Vérifie que l'utilisateur est bien dans la conversation
    if (Auth::id() != $conversation->user_one_id && Auth::id() != $conversation->user_two_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $conversation->delete();
    return response()->json(null, 204);
}

}
