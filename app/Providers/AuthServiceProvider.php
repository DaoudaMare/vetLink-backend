<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Produit;
use App\Models\Commande;
use App\Policies\ProduitPolicy;
use App\Policies\CommandePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Produit::class => ProduitPolicy::class,
        Commande::class => CommandePolicy::class,
        \App\Models\Conversation::class => \App\Policies\ConversationPolicy::class,
        \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Message::class => \App\Policies\MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
