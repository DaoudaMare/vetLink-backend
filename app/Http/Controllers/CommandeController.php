<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeRequest;
use App\Http\Requests\UpdateCommandeRequest;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    /**
     * Afficher toutes les commandes.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Commande::class);

        $commandes = Commande::with('produits')->get();
        return response()->json($commandes);
    }

    /**
     * Créer une nouvelle commande avec StoreCommandeRequest.
     */
    public function store(StoreCommandeRequest $request): JsonResponse
{
    $this->authorize('create', Commande::class);

    $commande = Commande::create([
        'user_id' => auth()->id(), // Utilise l'utilisateur connecté
        'statut' => 'en attente',
        'date_commande' => now(),
    ]);

    // Associer les produits avec les quantités
    $produits = collect($request->produits)->mapWithKeys(function ($produit) {
        return [$produit['id'] => ['quantite' => $produit['quantite']]];
    });

    $commande->produits()->sync($produits);

    return response()->json([
        'message' => 'Commande créée avec succès',
        'commande' => $commande->load('produits')
    ], 201);
}


    /**
     * Récupérer une commande spécifique.
     */
    public function show(Commande $commande): JsonResponse
    {
        $this->authorize('view', $commande);
        return response()->json($commande->load('produits'));
    }

    /**
     * Mettre à jour une commande avec UpdateCommandeRequest.
     */
    public function update(UpdateCommandeRequest $request, Commande $commande): JsonResponse
    {
        $this->authorize('update', $commande);

        $commande->update(['statut' => $request->statut]);

        // Mise à jour des produits si fournis
        if ($request->has('produits')) {
            $produits = collect($request->produits)->mapWithKeys(function ($produit) {
                return [$produit['id'] => ['quantite' => $produit['quantite']]];
            });

            $commande->produits()->sync($produits);
        }

        return response()->json([
            'message' => 'Commande mise à jour avec succès',
            'commande' => $commande->load('produits')
        ]);
    }

    /**
     * Supprimer une commande.
     */
    public function destroy(Commande $commande): JsonResponse
    {
         $this->authorize('delete', $commande);

        $commande->delete();
        return response()->json(['message' => 'Commande supprimée avec succès']);
    }

/**
 * Récupérer toutes les commandes d'un utilisateur spécifique.
 */
public function getMesCommandes(Request $request): JsonResponse
{
    $user = $request->user();

    $commandes = $user->commandes()->with('produits')->get();

    return response()->json($commandes);
}


/**
 * Récupérer l'historique des commandes filtré
 */
public function historiqueMesCommandes(Request $request, $filter = null): JsonResponse
{
    $user = $request->user();

    $commandes = $user->commandes()
        ->withStatut($filter)
        ->with(['produits' => fn($q) => $q->select('produits.id', 'nom_produit', 'prix')])
        ->orderBy('date_commande', 'desc')
        ->get()
        ->map(function ($commande) {
            return [
                'id' => $commande->id,
                'date' => $commande->date_commande->format('d M, Y'),
                'time' => $commande->date_commande->format('H:i'),
                'statut' => $commande->statut,
                'produits' => $commande->produits->map(fn($p) => [
                    'nom' => $p->nom_produit,
                    'prix' => $p->pivot->quantite * $p->prix,
                    'quantite' => $p->pivot->quantite
                ]),
                'total' => $commande->produits->sum(fn($p) => $p->pivot->quantite * $p->prix)
            ];
        });

    return response()->json([
        'filter' => $filter,
        'count' => $commandes->count(),
        'commandes' => $commandes
    ]);
}

/**
 * Fonction Tableau de bord - Commandes en cours
 */
public function commandesEnCours()
{
    $commandes = Commande::with([
            'user:id,nom_raison_sociale,adresse_physique',
            'produits:id' // Charger les produits liés on sélectionne juste l'ID, le reste vient du pivot
        ])
        ->where('statut', 'en cours')
        ->orderBy('date_commande', 'desc')
        ->get()
        ->map(function ($commande) {
            return [
                'id' => $commande->id,
                'date_commande' => $commande->date_commande->format('d/m/Y H:i'),
                'user' => [
                    'nom' => $commande->user->nom_raison_sociale,
                    'adresse' => $commande->user->adresse_physique
                ],
                'produits' => $commande->produits->map(fn($p) => [
                    'id' => $p->id,
                    'nom' => $p->nom,
                    'quantite' => $p->pivot->quantite,
                    'statut' => $p->pivot->statut
                ])
            ];
        });

    return response()->json($commandes);
}

/**
 * Fonction Tableau de bord - Livraisons du jour
 */
public function livraisonsAujourdhui()
{
    $today = now()->format('Y-m-d');

    $commandes = Commande::with([
            'user:id,nom_raison_sociale,adresse_physique',
            'produits:id' // Charger les produits liés
        ])
        ->where('statut', 'livrée')
        ->whereDate('date_commande', $today)
        ->get()
        ->map(function ($commande) {
            return [
                'id' => $commande->id,
                'date_commande' => $commande->date_commande->format('H:i'),
                'adresse_livraison' => $commande->user->adresse_physique,
                'produits' => $commande->produits->map(fn($p) => [
                    'id' => $p->id,
                    'nom' => $p->nom,
                    'quantite' => $p->pivot->quantite,
                    'statut' => $p->pivot->statut
                ])
            ];
        });

    return response()->json($commandes);
}

public function commandesParProducteur(Request $request): JsonResponse
{
    $producteur = $request->user();

    $query = Commande::whereHas('produits', function ($q) use ($producteur) {
        $q->where('producteur_id', $producteur->id);
    });

    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    if ($request->filled('date')) {
        $query->whereDate('date_commande', $request->date);
    }

    $commandes = $query->with([
        'produits' => function ($q) use ($producteur) {
            $q->where('producteur_id', $producteur->id);
        },
        'user:id,nom_raison_sociale,telephone,adresse_physique'
    ])->get();

    return response()->json($commandes);
}



public function updateStatutProduit(Request $request, Commande $commande, Produit $produit)
{
    $producteur = auth()->user();

    // Vérifie si ce produit appartient au producteur
    if ($produit->producteur_id !== $producteur->id) {
        return response()->json(['message' => 'Accès interdit à ce produit.'], 403);
    }

    // Vérifie que le produit fait partie de cette commande
    if (!$commande->produits()->where('produit_id', $produit->id)->exists()) {
        return response()->json(['message' => 'Ce produit ne fait pas partie de cette commande.'], 404);
    }

    // Valider les données
    $validated = $request->validate([
        'statut' => 'required|string|in:en cours,livrée,annulée,expediée'
    ]);

    // Mise à jour du statut dans la table pivot
    $commande->produits()->updateExistingPivot($produit->id, [
        'statut' => $validated['statut']
    ]);

    // Recharge les produits avec leurs statuts à jour
    $commande->load('produits');

    // Vérifie si tous les produits sont dans un statut final livrée ou annulée
    $tousFinal = $commande->produits->every(function ($p) {
        return in_array($p->pivot->statut, ['livrée', 'annulée']);
    });

    if ($tousFinal && $commande->statut !== 'validée') {
        // Incrémenter uniquement les produits livrés
        $commande->produits->each(function ($p) {
            if ($p->pivot->statut === 'livrée') {
                $p->increment('ventes', $p->pivot->quantite);
            }
        });

        // Marquer la commande comme validée
        $commande->update(['statut' => 'validée']);
    }

    return response()->json(['message' => 'Statut mis à jour avec succès.']);
}

public function mesStatistiquesCommandes(): JsonResponse
{
    $producteur = auth()->user();

    $commandes = Commande::whereHas('produits', function ($q) use ($producteur) {
        $q->where('producteur_id', $producteur->id);
    })
    ->with('produits')
    ->get();

    $statutCount = $commandes->groupBy('statut')->map->count();

    return response()->json([
        'message' => 'Statistiques des commandes du producteur connecté',
        'total_commandes' => $commandes->count(),
        'par_statut' => $statutCount,
    ]);
}



}
