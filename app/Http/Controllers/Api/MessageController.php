<?php

// app/Http/Controllers/API/MessageController.php

namespace App\Http\Controllers\API;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{


public function store(StoreMessageRequest $request)
{
    $data = [
        'conversation_id' => $request->conversation_id,
        'sender_id' => Auth::id(),
        'message' => $request->message,
        'is_read' => false
    ];

    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public');

        $data['attachment_path'] = $path;
        $data['attachment_type'] = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'video';
    }

    $data['product_id'] = $request->product_id;

    $message = Message::create($data);

    if ($request->product_id) {
        $message->load('product');
    }

    // Déclenche l'événement de notification
    broadcast(new NewMessageSent($message))->toOthers();

    return response()->json($message, 201);
}


    public function index($conversationId)
{
    // Marquer tous les messages comme lus quand on ouvre la conversation
    Message::where('conversation_id', $conversationId)
           ->where('sender_id', '!=', Auth::id())
           ->update(['is_read' => true]);

    return Message::where('conversation_id', $conversationId)
                ->with(['sender', 'product' => function($q) {
                    $q->select('id', 'name', 'price', 'measure');
                }])
                ->orderBy('created_at')
                ->get();
}

public function markAsRead($messageId)
{
    $message = Message::where('id', $messageId)
                ->whereHas('conversation', function($q) {
                    $q->where('user_one_id', Auth::id())
                      ->orWhere('user_two_id', Auth::id());
                })->firstOrFail();

    $message->update(['is_read' => true]);
    return response()->json($message);
}
}
