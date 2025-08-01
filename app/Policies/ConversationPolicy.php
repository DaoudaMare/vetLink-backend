<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->users->contains($user);
    }

    public function update(User $user, Conversation $conversation): bool
    {
        return $conversation->users->contains($user);
    }
}