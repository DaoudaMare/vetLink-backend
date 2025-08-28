<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminChatController extends Controller
{
    /**
     * Afficher toutes les conversations pour modération
     */
    public function index(Request $request)
    {
        $query = Conversation::with(['users', 'messages' => function($q) {
            $q->latest()->take(1);
        }]);
        
        // Filtres
        if ($request->has('flagged')) {
            $query->where('is_flagged', true);
        }
        
        if ($request->has('user_id')) {
            $query->whereHas('users', function($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('messages', function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%");
            });
        }
        
        $conversations = $query->latest('updated_at')->paginate(20);
        $users = User::all();
        
        return view('admin.chat.index', compact('conversations', 'users'));
    }
    
    /**
     * Afficher une conversation spécifique
     */
    public function show(Conversation $conversation)
    {
        $conversation->load(['users', 'messages.user']);
        $messages = $conversation->messages()->with('user')->latest()->paginate(50);
        
        return view('admin.chat.show', compact('conversation', 'messages'));
    }
    
    /**
     * Signaler une conversation
     */
    public function flag(Request $request, Conversation $conversation)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $conversation->update([
            'is_flagged' => true,
            'flag_reason' => $request->reason,
            'flagged_by' => auth()->id(),
            'flagged_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Conversation signalée avec succès');
    }
    
    /**
     * Retirer le signalement d'une conversation
     */
    public function unflag(Conversation $conversation)
    {
        $conversation->update([
            'is_flagged' => false,
            'flag_reason' => null,
            'flagged_by' => null,
            'flagged_at' => null
        ]);
        
        return redirect()->back()
            ->with('success', 'Signalement retiré avec succès');
    }
    
    /**
     * Supprimer un message
     */
    public function deleteMessage(Message $message)
    {
        // Supprimer le fichier attaché s'il existe
        if ($message->attachment_path && Storage::disk('private')->exists($message->attachment_path)) {
            Storage::disk('private')->delete($message->attachment_path);
        }
        
        $message->update([
            'content' => '[Message supprimé par l\'administrateur]',
            'attachment_path' => null,
            'attachment_name' => null,
            'attachment_type' => null,
            'deleted_by_admin' => true,
            'deleted_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Message supprimé avec succès');
    }
    
    /**
     * Suspendre un utilisateur du chat
     */
    public function suspendUser(Request $request, User $user)
    {
        $request->validate([
            'suspension_duration' => 'required|integer|min:1|max:365', // jours
            'reason' => 'required|string|max:500'
        ]);
        
        $user->update([
            'chat_suspended_until' => now()->addDays($request->suspension_duration),
            'chat_suspension_reason' => $request->reason
        ]);
        
        return redirect()->back()
            ->with('success', "Utilisateur suspendu du chat pour {$request->suspension_duration} jour(s)");
    }
    
    /**
     * Lever la suspension d'un utilisateur
     */
    public function unsuspendUser(User $user)
    {
        $user->update([
            'chat_suspended_until' => null,
            'chat_suspension_reason' => null
        ]);
        
        return redirect()->back()
            ->with('success', 'Suspension levée avec succès');
    }
    
    /**
     * Fermer une conversation
     */
    public function closeConversation(Request $request, Conversation $conversation)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $conversation->update([
            'is_closed' => true,
            'closed_reason' => $request->reason,
            'closed_by' => auth()->id(),
            'closed_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Conversation fermée avec succès');
    }
    
    /**
     * Rouvrir une conversation
     */
    public function reopenConversation(Conversation $conversation)
    {
        $conversation->update([
            'is_closed' => false,
            'closed_reason' => null,
            'closed_by' => null,
            'closed_at' => null
        ]);
        
        return redirect()->back()
            ->with('success', 'Conversation rouverte avec succès');
    }
    
    /**
     * Messages signalés
     */
    public function flaggedMessages()
    {
        $messages = Message::where('is_flagged', true)
            ->with(['user', 'conversation'])
            ->latest()
            ->paginate(20);
            
        return view('admin.chat.flagged-messages', compact('messages'));
    }
    
    /**
     * Statistiques du chat
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);
        
        $stats = [
            'total_conversations' => Conversation::where('created_at', '>=', $startDate)->count(),
            'total_messages' => Message::where('created_at', '>=', $startDate)->count(),
            'flagged_conversations' => Conversation::where('is_flagged', true)->count(),
            'closed_conversations' => Conversation::where('is_closed', true)->count(),
            'suspended_users' => User::where('chat_suspended_until', '>', now())->count(),
            'messages_with_attachments' => Message::whereNotNull('attachment_path')
                ->where('created_at', '>=', $startDate)
                ->count(),
        ];
        
        // Activité quotidienne
        $dailyActivity = Message::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Utilisateurs les plus actifs
        $activeUsers = User::withCount(['messages' => function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            }])
            ->orderBy('messages_count', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.chat.statistics', compact('stats', 'dailyActivity', 'activeUsers', 'period'));
    }
    
    /**
     * Envoyer un message d'avertissement
     */
    public function sendWarning(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        // Créer une conversation système avec l'utilisateur
        $conversation = Conversation::create([
            'is_system' => true,
            'subject' => 'Avertissement Administrateur'
        ]);
        
        $conversation->users()->attach([auth()->id(), $user->id]);
        
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'content' => "⚠️ AVERTISSEMENT ADMINISTRATEUR ⚠️\n\n" . $request->message,
            'is_system_message' => true
        ]);
        
        return redirect()->back()
            ->with('success', 'Avertissement envoyé avec succès');
    }
}
