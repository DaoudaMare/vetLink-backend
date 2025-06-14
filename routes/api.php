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
});


// Route pour récupérer tous les utilisateurs via le UserController
Route::get('/getUser', [UserController::class, 'index']);
