<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommandePolicy
{
    /**
     * L'utilisateur peut voir toutes les commandes (ex : admin ou producteur).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * L'utilisateur peut voir une commande spécifique.
     */
    public function view(User $user, Commande $commande): bool
    {
        return $user->id === $commande->user_id || $user->role === 'admin';
    }

    /**
     * L'utilisateur peut créer une commande.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * L'utilisateur peut mettre à jour une commande.
     */
    public function update(User $user, Commande $commande): bool
    {
        return $user->id === $commande->user_id || $user->role === 'admin';
    }

    /**
     * L'utilisateur peut supprimer une commande.
     */
    public function delete(User $user, Commande $commande): bool
    {
        return $user->role === 'admin';
    }

    /**
     * L'utilisateur peut restaurer une commande.
     */
    public function restore(User $user, Commande $commande): bool
    {
        return $user->role === 'admin';
    }

    /**
     * L'utilisateur peut forcer la suppression d'une commande.
     */
    public function forceDelete(User $user, Commande $commande): bool
    {
        return $user->role === 'admin';
    }
}
