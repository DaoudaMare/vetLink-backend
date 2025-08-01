<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Un utilisateur peut voir un autre profil s'il est le propriétaire, un admin ou un modérateur.
     */
    public function view(User $currentUser, User $userToView): bool
    {
        return $currentUser->id === $userToView->id || $currentUser->isAdmin() || $currentUser->isModerateur();
    }

    /**
     * Un utilisateur peut mettre à jour un autre profil s'il est le propriétaire ou un admin.
     * (Les modérateurs ne peuvent généralement pas modifier les profils)
     */
    public function update(User $currentUser, User $userToUpdate): bool
    {
        return $currentUser->id === $userToUpdate->id || $currentUser->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin();
    }
}
