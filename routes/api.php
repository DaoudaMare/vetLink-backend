<?php

// use App\Models\User;
// use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ProduitController;

use App\Http\Controllers\SecteurController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SousSecteurController;
use App\Http\Controllers\Api\AuthentificationController;
use App\Http\Controllers\Api\ProfileProggressController;
use App\Http\Controllers\Api\ProducerController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\CommandeController;


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

// Routes publiques pour les produits
Route::get('/products', [ProduitController::class, 'index']);
Route::get('/products/{product}', [ProduitController::class, 'show']);
Route::get('/categories', [CustomerController::class, 'categories']);



Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthentificationController::class, 'logout']);
    
    // Routes utilisateurs
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    
    // Routes profil progress
    Route::get('/profile-progress/{user_id}', [ProfileProggressController::class, 'show']);
    Route::put('/profile-progress/{id}', [ProfileProggressController::class, 'update']);
    
    // Routes producteurs
    Route::prefix('producer')->group(function () {
        Route::get('/profile', [ProducerController::class, 'profile']);
        Route::get('/products', [ProducerController::class, 'myProducts']);
        Route::post('/products', [ProducerController::class, 'createProduct']);
        Route::put('/products/{product}', [ProducerController::class, 'updateProduct']);
        Route::delete('/products/{product}', [ProducerController::class, 'deleteProduct']);
        Route::get('/orders', [ProducerController::class, 'myOrders']);
        Route::get('/statistics', [ProducerController::class, 'statistics']);
    });
    
    // Routes clients
    Route::prefix('customer')->group(function () {
        Route::get('/profile', [CustomerController::class, 'profile']);
        Route::get('/search-products', [CustomerController::class, 'searchProducts']);
        Route::get('/products/{product}', [CustomerController::class, 'productDetails']);
        Route::post('/orders', [CustomerController::class, 'placeOrder']);
        Route::get('/orders', [CustomerController::class, 'orderHistory']);
        Route::get('/orders/{order}', [CustomerController::class, 'orderDetails']);
        Route::put('/orders/{order}/cancel', [CustomerController::class, 'cancelOrder']);
        Route::get('/recommended-products', [CustomerController::class, 'recommendedProducts']);
    });
    
    // Routes notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::put('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/', [NotificationController::class, 'destroy']);
    });
    
    // Routes évaluations
    Route::prefix('reviews')->group(function () {
        Route::get('/products/{product}', [ReviewController::class, 'productReviews']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::put('/', [ReviewController::class, 'update']);
        Route::delete('/', [ReviewController::class, 'destroy']);
    });
    
    // Routes produits (admin/producteur)
    Route::get('produits', [ProduitController::class, 'index']);
    Route::post('produits', [ProduitController::class,'store']);
    
    // Routes commandes
    Route::get('/commande', [CommandeController::class, 'index']);
    Route::post('/commande', [CommandeController::class, 'store']);
    Route::get('/{commande}', [CommandeController::class, 'show']);
    Route::put('/{commande}', [CommandeController::class, 'update']);
    Route::delete('/{commande}', [CommandeController::class, 'destroy']);
    Route::put('/{commande}/delivery-status', [CommandeController::class, 'updateDeliveryStatus']);
    Route::put('/{commande}/payment-status', [CommandeController::class, 'updatePaymentStatus']);
    Route::get('/customer/{customerId}', [CommandeController::class, 'customerOrders']);
});

// Routes pour les commandes
Route::prefix('commandes')->group(function () {
   
});
