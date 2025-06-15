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



Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthentificationController::class, 'logout']);
    Route::get('produits', [ProduitController::class, 'index']);
    Route::post('produits', [ProduitController::class,'store']);
    Route::get('/commande', [CommandeController::class, 'index']);
    Route::post('/commande', [CommandeController::class, 'store']);
    Route::get('/{commande}', [CommandeController::class, 'show']);
    Route::put('/{commande}', [CommandeController::class, 'update']);
    Route::delete('/{commande}', [CommandeController::class, 'destroy']);
    Route::put('/{commande}/delivery-status', [CommandeController::class, 'updateDeliveryStatus']);
    Route::put('/{commande}/payment-status', [CommandeController::class, 'updatePaymentStatus']);
    Route::get('/customer/{customerId}', [CommandeController::class, 'customerOrders']);
});


// Route pour récupérer tous les utilisateurs via le UserController
Route::get('/getUser', [UserController::class, 'index']);

// Routes pour les commandes
Route::prefix('commandes')->group(function () {
   
});
