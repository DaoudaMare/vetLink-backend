<?php

use App\Http\Controllers\Api\AuthentificationController;
use App\Http\Controllers\Api\ProfileProggressController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ProduitController;
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

Route::post('/register', [AuthentificationController::class, 'register']);
Route::post('/login', [AuthentificationController::class, 'login']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('profile_progress', ProfileProggressController::class);
    Route::post('/logout', [AuthentificationController::class, 'logout']);
    Route::apiResource('produits', ProduitController::class);
});
