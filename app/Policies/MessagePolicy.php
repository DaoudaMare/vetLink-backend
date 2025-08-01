<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can mark the message as read.
     */
    public function markAsRead(User $user, Message $message): bool
    {
        // L'utilisateur peut marquer le message comme lu si :
        // 1. Il fait partie de la conversation.
        // 2. Il n'est PAS l'auteur du message.
        return $message->conversation->users->contains($user) && $message->user_id !== $user->id;
    }
}