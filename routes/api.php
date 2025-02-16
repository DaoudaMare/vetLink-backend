<?php
//use App\Http\Controllers\PaiementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthentificationController;
use App\Http\Controllers\PaiementController;


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


// Route::prefix('payments')->group(function () {
    
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::post('/logout', [AuthentificationController::class, 'logout']);



    Route::post('/store', [PaiementController::class, 'store']);
    Route::get('/', [PaiementController::class, 'index']);
    Route::get('/{id}', [PaiementController::class, 'show']);
    Route::post('/{id}/confirm', [PaiementController::class, 'confirm']);
    Route::post('/{id}/cancel', [PaiementController::class, 'cancel']);
    Route::post('/{id}/refund', [PaiementController::class, 'refund']);
});
