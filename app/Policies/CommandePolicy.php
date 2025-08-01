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
     * le producteur du produit commandé, ou un admin.
     */
    public function view(User $user, Commande $commande): bool
    {
        return $user->id === $commande->customer_id || ($commande->produit && $user->id === $commande->produit->producer_id) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Un producteur peut mettre à jour le statut d'une commande de son produit.
     */
    public function updateStatus(User $user, Commande $commande): bool
    {
        return ($commande->produit && $user->id === $commande->produit->producer_id) || $user->isAdmin();
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
