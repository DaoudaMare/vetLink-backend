<?php

use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\Api\AuthentificationController;
use App\Http\Controllers\Api\ProfileProggressController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProducteurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\SousSecteurController;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

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
    // completer profile producteur
    Route::apiResource('producers', ProducteurController::class);


    // Gestion de la classification sectorielle complète
    Route::apiResource('secteurs', SecteurController::class)->except(['index', 'show']);
    Route::apiResource('sous-secteurs', SousSecteurController::class)->except(['index', 'show']);
    Route::apiResource('activites', ActiviteController::class)->except(['index', 'show']);


    // produit
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
    Route::apiResource('commandes', CommandeController::class);
    Route::get('/commandes/user/{user}', [CommandeController::class, 'getCommandesByUser']);

    // Historique Commandes avec filtres
    Route::get('/commandes/user/{user}/historique/{filter?}', [CommandeController::class, 'historique'])
    ->where('filter', 'retirer|recus')  ;
    // Tableau de bord - Commandes en cours
    Route::get('/commandes/encours', [CommandeController::class, 'commandesEnCours']);
    // Tableau de bord - Livraisons du jour
    Route::get('/commandes/livraisons-aujourdhui', [CommandeController::class, 'livraisonsAujourdhui']);


    // Statistiques
    Route::get('/stats/secteurs', [SecteurController::class, 'stats']);

});
