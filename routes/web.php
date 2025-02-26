<?php

use App\Http\Controllers\Admin\Gamifications\GamificationController;
use App\Http\Controllers\Admin\Notifications\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ViewLoginController;
use App\Http\Controllers\Admin\ViewBadgeController;
use App\Http\Controllers\Admin\ViewDashboardController;
use App\Http\Controllers\Admin\Produits\ProduitController;
use App\Http\Controllers\Admin\Transactions\TransactionController;
use App\Http\Controllers\Admin\Utilisateurs\UtilisateursController;
use App\Http\Controllers\Auth\StoreLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('vetlink')->group(function () {
    Route::get('login', ViewLoginController::class)->name('login');
    Route::post('authenticate', StoreLoginController::class)->name('authenticate');
});

Route::middleware(['auth'])->prefix('vetlink')->name('vetlink.admin.')->group(function () {
    Route::get('dashboard', ViewDashboardController::class)->name('dashboard');

   //Routes pour Utilisateurs
    Route::get('utilisateurs', [UtilisateursController::class, 'listeUtilisateurs'])->name('users.utilisateurs');
    Route::get('verification_profile', [UtilisateursController::class, 'verificationProfile'])->name('users.verification_profile');
    Route::get('new_moderateur', [UtilisateursController::class, 'new_User'])->name('users.new_moderateur');

    //Routes pour Badges
    Route::get('badge_verifier_verifier', ViewBadgeController::class)->name('badge_verifier');

    //Routes pour Produits
    Route::get('liste_produits', [ProduitController::class, 'listeProduits'])->name('produits.liste_produits');
    Route::get('certifications_produit', [ProduitController::class, 'certificationsProduit'])->name('produits.certifications_produit');
    Route::get('traceability_produit', [ProduitController::class, 'traceabilityProduit'])->name('produits.traceability_produit');

    //Routes pour Transactions
    Route::get('listes_transactions', [TransactionController::class, 'listesTransactions'])->name('transactions.listes_transactions');
    Route::get('transaction_litiges', [TransactionController::class, 'transactionLitiges'])->name('transactions.transactions_litiges');

    //Routes pour Gamefication & Rewards
    Route::get('classement', [GamificationController::class, 'classement'])->name('gamification.classement');
    Route::get('defis', [GamificationController::class, 'defis'])->name('gamification.defis');
    Route::get('rewards', [GamificationController::class, 'rewards'])->name('gamification.rewards');

    //Routes pour Notifications & Messages
    Route::get('notifications', [NotificationController::class, 'listeNotifications'])->name('notifications.notifications');
    Route::get('parametres_notifications', [NotificationController::class, 'parametre'])->name('notifications.parametres');

});