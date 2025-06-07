<?php
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\Api\AssociationController;
use App\Http\Controllers\Api\AuthentificationController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\EntrepriseController;
use App\Http\Controllers\Api\GroupementController;
use App\Http\Controllers\Api\ParticulierController;
use App\Http\Controllers\Api\ProfileProggressController;
use App\Http\Controllers\Api\StartupController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\AvisProducteurController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProducteurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\SousSecteurController;
use App\Models\Producteur;
use App\Models\ProfileProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Routes publiques
Route::post('/register', [AuthentificationController::class, 'register']);
Route::post('/login', [AuthentificationController::class, 'login']);

// Routes pour la classification sectorielle accessibles sans auth
Route::apiResource('secteurs', SecteurController::class)->only(['index', 'show']);
Route::apiResource('sous-secteurs', SousSecteurController::class)->only(['index', 'show']);
Route::apiResource('activites', ActiviteController::class)->only(['index', 'show']);

// Recherche de produits par secteur
Route::get('/secteurs/{secteur}/produits', [SecteurController::class, 'produits']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Gestion des utilisateurs
    Route::apiResource('users', UserController::class);
    Route::apiResource('profile_progress', ProfileProggressController::class);
    Route::post('/logout', [AuthentificationController::class, 'logout']);
    Route::apiResource('producers', ProducteurController::class);
    Route::put('/profile/producteurs/update', [ProducteurController::class, 'update']);
    //Affichage de la progression du profile du producteur connecté
    Route::get('/progression-mon-profile/profile-progress', [ProfileProggressController::class, 'mon_profile_progresse']);

    // Gestion de la classification sectorielle complète
    Route::apiResource('secteurs', SecteurController::class)->except(['index', 'show']);
    Route::apiResource('sous-secteurs', SousSecteurController::class)->except(['index', 'show']);
    Route::apiResource('activites', ActiviteController::class)->except(['index', 'show']);

    // Avis
    Route::post('/produits/avis', [AvisController::class, 'store']);
    Route::post('/avis-producteurs', [AvisProducteurController::class, 'store']);
    Route::get('/produits/{id}/avis', [AvisController::class, 'avisParProduit']);
    Route::get('/producteurs/{id}/avis', [AvisProducteurController::class, 'avisParProducteur']);

    // Produits par producteur et autres filtres
    Route::get('/producteurs/mes-statistiques-ventes', [ProduitController::class, 'mesStatistiquesVentes']);
    Route::get('/produits/producteur/{producteur_id}', [ProduitController::class, 'produitsParProducteur']);
    Route::get('/produits/search', [ProduitController::class, 'search']);
    Route::get('/produits/sous-secteur/{sousSecteur}', [ProduitController::class, 'produitsParSousSecteur']);
    Route::get('/produits/activite/{activite}', [ProduitController::class, 'produitsParActivite']);
    Route::get('/produits/top-vendus', [ProduitController::class, 'topVendus']);
    Route::get('/produits/top-apprecies', [ProduitController::class, 'topApprecies']);
    Route::get('/produits/tri/prix-asc', [ProduitController::class, 'triParPrixAsc']);
    Route::get('/produits/tri/prix-desc', [ProduitController::class, 'triParPrixDesc']);
    Route::get('/produits/recents', [ProduitController::class, 'produitsRecents']);
    Route::apiResource('produits', ProduitController::class);

    // Commandes
    Route::get('/producteurs/mes-statistiques-commandes', [CommandeController::class, 'mesStatistiquesCommandes']);
    Route::get('/mes-commandes', [CommandeController::class, 'getMesCommandes']);
    Route::get('/mes-commandes/historique/{filter?}', [CommandeController::class, 'historiqueMesCommandes'])->where('filter', 'retirer|recus');
    Route::get('/commandes/encours', [CommandeController::class, 'commandesEnCours']);
    Route::get('/commandes/livraisons-aujourdhui', [CommandeController::class, 'livraisonsAujourdhui']);
    Route::put('/commandes/{commande}/produits/{produit}/statut', [CommandeController::class, 'updateStatutProduit']);
    Route::get('/mes-commandes/producteur', [CommandeController::class, 'commandesParProducteur']);
    Route::apiResource('commandes', CommandeController::class);

    Route::prefix('completer-profile')->group(function () {
    Route::post('/particuliers', [ParticulierController::class, 'store']);
    Route::post('/associations', [AssociationController::class, 'store']);
    Route::post('/entreprises', [EntrepriseController::class, 'store']);
    Route::post('/groupements', [GroupementController::class, 'store']);
    Route::post('/startups', [StartupController::class, 'store']);
    Route::post('/document', [DocumentController::class, 'store']);
});


    // Statistiques
    Route::get('/stats/secteurs', [SecteurController::class, 'stats']);
});
