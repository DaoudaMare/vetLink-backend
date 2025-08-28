<?php

namespace App\Policies;

use App\Models\Commande;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommandePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Un utilisateur peut voir une commande si il est le client,
     * un producteur concerné par la commande, ou un admin.
     */
    public function view(User $user, Commande $commande): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $commande->customer_id) {
            return true;
        }

        // Vérifie si au moins un des produits de la commande appartient au producteur.
        return $commande->produits()->where('producer_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Un producteur peut mettre à jour le statut d'une commande qui contient un de ses produits.
     */
    public function updateStatus(User $user, Commande $commande): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Vérifie si au moins un des produits de la commande appartient au producteur.
        return $commande->produits()->where('producer_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commande $commande): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commande $commande): bool
    {
        return $user->isAdmin();
    }

    /**
     * Un client peut annuler sa propre commande sous certaines conditions.
     */
    public function cancel(User $user, Commande $commande): bool
    {
        return $user->id === $commande->customer_id;
    }
}
