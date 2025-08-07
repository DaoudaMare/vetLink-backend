<?php

// use App\Models\User;
// use Illuminate\Support\Str;
use App\Http\Controllers\ActiviteController;
// use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\AuthentificationController;
// use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\CustomerController;

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProducerController;
use App\Http\Controllers\API\ProfilePhotoController;
use App\Http\Controllers\Api\ProfileProgressController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SecteurController;
use App\Http\Controllers\SousSecteurController;
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

// Routes publiques pour les produits
Route::get('/products', [ProduitController::class, 'index']);
Route::get('/categories', [CustomerController::class, 'categories']);
Route::get('/user-types', [App\Http\Controllers\Api\UserTypeController::class, 'index']);
Route::get('/organization-types', [App\Http\Controllers\Api\OrganizationController::class, 'getTypes']);
Route::get('/business-sectors', [App\Http\Controllers\Api\OrganizationController::class, 'getSectors']);
Route::get('/statuses', [App\Http\Controllers\Api\StatusController::class, 'index']);



Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthentificationController::class, 'logout']);

    // Routes utilisateurs
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

     Route::post('/profile-photo', [ProfilePhotoController::class, 'update']);

    // Routes profil progress
    Route::get('/profile-progress/{user_id}', [ProfileProgressController::class, 'show']);
    Route::put('/profile-progress/{id}', [ProfileProgressController::class, 'update']);

    // Routes producteurs
    Route::prefix('producer')->group(function () {
        Route::get('/profile', [ProducerController::class, 'profile']);
        Route::get('/products', [ProducerController::class, 'myProducts']);
        Route::post('/products', [ProducerController::class, 'createProduct']);
        Route::put('/products/{product}', [ProducerController::class, 'updateProduct']);
        Route::delete('/products/{product}', [ProducerController::class, 'deleteProduct']);
        Route::post('/products/{product}/images', [ProducerController::class, 'addProductImages']);
        Route::delete('/products/{product}/images/{image}', [ProducerController::class, 'deleteProductImage']);
        Route::get('/orders', [ProducerController::class, 'myOrders']);
        Route::get('/statistics', [ProducerController::class, 'statistics']);
        Route::get('/orders/{order}', [ProducerController::class, 'showOrder']);
        Route::put('/orders/{order}/status', [ProducerController::class, 'updateOrderStatus']);
    });

    // Routes clients
    Route::prefix('customer')->group(function () {
        Route::get('/profile', [CustomerController::class, 'profile']);
        Route::get('/search-products', [CustomerController::class, 'searchProducts']);
        Route::get('/orders/today', [CustomerController::class, 'todaysOrders']);
        Route::get('/orders/current', [CustomerController::class, 'currentOrders']);
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



});

// Routes pour les commandes


// Routes pour le chat
Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::get('/conversations', [App\Http\Controllers\Api\ChatController::class, 'conversations']);
    Route::get('/conversations/{conversation}/messages', [App\Http\Controllers\Api\ChatController::class, 'messages']);
    Route::post('/conversations/{conversation}/messages', [App\Http\Controllers\Api\ChatController::class, 'sendMessage']);
    Route::post('/conversations/start', [App\Http\Controllers\Api\ChatController::class, 'startConversation']);
    Route::post('/messages/{message}/read', [App\Http\Controllers\Api\ChatController::class, 'markAsRead']);
    Route::delete('/conversations/{conversation}', [App\Http\Controllers\Api\ChatController::class, 'leaveConversation']);
});

// Routes pour les documents
Route::middleware('auth:sanctum')->prefix('documents')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\DocumentController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\DocumentController::class, 'store']);
    Route::get('/{document}', [App\Http\Controllers\Api\DocumentController::class, 'show']);
    Route::delete('/{document}', [App\Http\Controllers\Api\DocumentController::class, 'destroy']);
});
