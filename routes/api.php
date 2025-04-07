<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\Api\UserController;
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

Route::post('/register', [AuthentificationController::class, 'register']);
Route::post('/login', [AuthentificationController::class, 'login']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('profile_progress', ProfileProggressController::class);
    Route::post('/logout', [AuthentificationController::class, 'logout']);
    Route::apiResource('produits', ProduitController::class);
});


Route::get('/getUser', function (Request $request) {
    return response()->json($request->user());
});


Route::post('/addUser',function (Request $request) {
    $user = new User();
    $user->nom_raison_sociale = $request->input('agriculteur');
    $user->type_user = $request->input('agriculteur');
    $user->secteur_activite = $request->input('agriculture');
    $user->email = $request->input('daudamare21@gmail.com');
    $user->telephone = $request->input('74856322');
    $user->liens_reseaux_sociaux = json_encode($request->input('liens_reseaux_sociaux'));
    $user->password = Hash::make($request->input('daoudamare12'));
    $user->pays = $request->input('Burkina Faso');
    $user->ville = $request->input('Bobo Dioulasso');
    $user->coordonnees_gps = $request->input('12.345678, 98.765432');
    $user->adresse_physique = $request->input('adresse_physique');
    $user->description = $request->input('Je suis un pationner de l\'agriculture');
    
   
    // Enregistrez l'utilisateur dans la base de données
    if ($user->save()) {
        return response()->json(['message' => 'Utilisateur ajouté avec succès'], 201);
    } else {
        return response()->json(['message' => 'Erreur lors de l\'ajout de l\'utilisateur'], 500);
    }
});