<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
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

// Routes pour la classification sectorielle (accessibles sans auth)
Route::apiResource('secteurs', SecteurController::class)->only(['index', 'show']);
Route::apiResource('sous-secteurs', SousSecteurController::class)->only(['index', 'show']);
Route::apiResource('activites', ActiviteController::class)->only(['index', 'show']);
// Recherche de produits par secteur (publique)
Route::get('/secteurs/{secteur}/produits', [SecteurController::class, 'produits']);


Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class,'index']);
    Route::apiResource('profile_progress', ProfileProggressController::class);
    Route::post('/logout', [AuthentificationController::class, 'logout']);
    Route::get('produits', [ProduitController::class, 'index']);
});


Route::get('/getUser', function () {
    return User::all();
});




Route::get('/addUserTest', function () {
    try {
        $user = User::create([
            'id' => Str::uuid(),
            'nom_raison_sociale' => 'Tech Elevage SARL',
            'type_user' => 'entreprise',
            'secteur_activite' => 'elevage',
            'email' => 'contact@techinnov.com',
            'telephone' => '+22670000000',
            'pays' => 'Burkina Faso',
            'ville' => 'Ouagadougou',
            'coordonnees_gps' => '12.3714,-1.5197',
            'adresse_physique' => 'Zone du bois',
            'photo_profil' => 'https://example.com/photos/profil1.jpg',
            'description' => 'Entreprise eleveag dans les TIC.',
            'password' => Hash::make('password123'),
            'liens_reseaux_sociaux' => json_encode([
                'facebook' => 'https://facebook.com/techinnov',
                'linkedin' => 'https://linkedin.com/company/techinnov'
            ]),
            'user_id' => null,
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès !',
            'user' => $user
        ], 201);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Erreur lors de la création',
            'error' => $e->getMessage()
        ], 500);
    }
});
