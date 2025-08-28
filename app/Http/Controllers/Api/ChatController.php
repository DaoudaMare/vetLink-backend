<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function conversations(Request $request)
    {
        $query = Auth::user()->conversations()->wherePivot('deleted_at', null)->with('users.userType');

        // Filtre pour les messages non lus
        if ($request->query('filter') === 'unread') {
            $query->whereHas('messages', function ($q) {
                $q->where('user_id', '!=', Auth::id())->whereNull('read_at');
            });
        }

        // Filtre pour le rôle des participants
        if ($role = $request->query('with_role')) {
            $query->whereHas('users', function ($q) use ($role) {
                $q->where('id', '!=', Auth::id())->whereHas('userType', function ($subQ) use ($role) {
                    $subQ->where('title', $role);
                });
            });
        }

        $conversations = $query->latest('updated_at')->get();

        return response()->json([
            'message' => 'Conversations récupérées avec succès',
            'data' => ConversationResource::collection($conversations)
        ], 200);
    }

    public function messages(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $perPage = $request->query('per_page', 15);
        $messages = $conversation->messages()->with('user')->paginate($perPage);
        
        return response()->json([
            'message' => 'Messages récupérés avec succès',
            'data' => MessageResource::collection($messages->items()),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ]
        ], 200);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        $request->validate([
            'body' => 'required_without:file|nullable|string|max:4096',
            'file' => 'required_without:body|nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi,pdf,doc,docx,xls,xlsx|max:25600', // 25MB Max
        ]);

        $messageData = [
            'sender_id' => Auth::id(),
            'message' => $request->body,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->extension();
            $newName = time() . '.' . $extension;

            // Déterminer le type et le dossier de stockage
            $type = 'document'; // Type par défaut
            if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
                $type = 'image';
            } elseif (in_array($extension, ['mp4', 'mov', 'avi'])) {
                $type = 'video';
            }

            $path = $file->storeAs('chat_files/' . $type . 's', $newName, 'public');

            $messageData['attachment_type'] = $type;
            $messageData['attachment_path'] = $path;
            $messageData['file_name'] = $originalName;
        } else {
            $messageData['message_type'] = 'text';
        }

        $message = $conversation->messages()->create($messageData);

        // Broadcasting désactivé pour les tests
        // if (config('broadcasting.default') !== 'null') {
        //     broadcast(new MessageSent($message))->toOthers();
        // }

        return response()->json($message->load('user'));
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'nullable|exists:produits,id',
            'order_id' => 'nullable|exists:commandes,id',
        ]);

        $conversation = Conversation::create([
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
        ]);

        $conversation->users()->attach([Auth::id(), $request->user_id]);

        return response()->json($conversation->load('users'));
    }

    public function markAsRead(Message $message)
    {
        $this->authorize('markAsRead', $message);

        if (is_null($message->read_at)) {
            $message->update(['read_at' => now()]);
        }

        return response()->json(['message' => 'Message marqué comme lu.']);
    }

    public function leaveConversation(Conversation $conversation)
    {
        $this->authorize('update', $conversation);

        $conversation->users()->updateExistingPivot(Auth::id(), [
            'deleted_at' => now(),
        ]);

        return response()->json(['message' => 'Vous avez quitté la conversation.']);
    }
}