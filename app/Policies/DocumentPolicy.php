<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        return $user->id === $document->user_id || $user->isAdmin() || $user->isModerateur();
    }

    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent téléverser des documents
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->id === $document->user_id || $user->isAdmin() || $user->isModerateur();
    }
}