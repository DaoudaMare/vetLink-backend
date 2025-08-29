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
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function conversations(Request $request)
    {
        $query = Auth::user()->conversations()->wherePivot('deleted_at', null)->with('users.userType');

        // Filtre pour les messages non lus
        if ($request->query('filter') === 'unread') {
            $query->whereHas('messages', function ($q) {
                $q->where('sender_id', '!=', Auth::id())->where('is_read', false); // ✅ Corrigé
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

        // ✅ Corrigé : charger la relation 'user' qui pointe vers 'sender_id'
        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc') // ✅ Ajouté : ordre chronologique
            ->paginate($perPage);

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

        // ✅ Corrigé : utiliser les bons noms de champs de votre DB
        $messageData = [
            'sender_id' => Auth::id(),
            'message' => $request->body, // ✅ 'message' pas 'content'
            'is_read' => false, // ✅ Valeur par défaut
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
            // ✅ Supprimé 'file_name' car ce champ n'existe pas dans votre DB
        }

        $message = $conversation->messages()->create($messageData);

        // Broadcasting désactivé pour les tests
        // if (config('broadcasting.default') !== 'null') {
        //     broadcast(new MessageSent($message))->toOthers();
        // }

        // ✅ Retourner avec MessageResource pour cohérence
        return response()->json([
            'message' => 'Message envoyé avec succès',
            'data' => new MessageResource($message->load('user'))
        ], 201);
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

        return response()->json([
            'message' => 'Conversation créée avec succès',
            'data' => new ConversationResource($conversation->load('users'))
        ], 201);
    }

    public function markAsRead(Message $message)
    {
        $this->authorize('markAsRead', $message);

        // ✅ Corrigé : utiliser 'is_read' qui existe dans votre DB
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
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

    public function downloadAttachment(Message $message)
    {
        // ✅ Vérifier que l'utilisateur fait partie de la conversation
        if (!$message->conversation->users->contains(auth()->id())) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // ✅ Vérifier l'existence du champ attachment_path
        if (!$message->attachment_path) {
            return response()->json(['error' => 'Aucun fichier attaché'], 404);
        }

        // ✅ Construire le chemin complet du fichier
        $filePath = storage_path('app/public/' . $message->attachment_path);

        // ✅ Vérifier que le fichier existe physiquement
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'Fichier introuvable sur le serveur'], 404);
        }

        // ✅ Générer un nom de fichier pour le téléchargement
        $downloadName = basename($message->attachment_path);

        // ✅ Si vous voulez garder le nom original, vous pouvez utiliser :
        // $downloadName = 'attachment_' . $message->id . '.' . pathinfo($message->attachment_path, PATHINFO_EXTENSION);

        return response()->download($filePath, $downloadName);
    }
}
