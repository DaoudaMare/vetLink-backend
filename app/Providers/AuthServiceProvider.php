<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Commande;
use App\Models\Produit;
use App\Policies\CommandePolicy;
use App\Policies\ProduitPolicy;
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
