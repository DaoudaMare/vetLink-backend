<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
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



Route::get('/addUser', function (Request $request) {
    // 1. Validation des données avec messages personnalisés
    $validator = Validator::make($request->all(), [
        'nom_raison_sociale' => 'required|string|max:255',
        'type_user' => 'required|string|in:agriculteur,eleveur,autre',
        'secteur_activite' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'telephone' => 'required|string|max:20',
        'password' => 'required|string|min:8',
        'pays' => 'required|string',
        'ville' => 'required|string',
        'coordonnees_gps' => 'nullable|string',
        'adresse_physique' => 'nullable|string',
        'description' => 'nullable|string|max:500',
        'liens_reseaux_sociaux' => 'nullable|array'
    ], [
        'required' => 'Le champ :attribute est obligatoire.',
        'email.unique' => 'Cet email est déjà utilisé.'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    // 2. Création de l'utilisateur
    try {
        $user = User::create([
            'nom_raison_sociale' => $request->nom_raison_sociale,
            'type_user' => $request->type_user,
            'secteur_activite' => $request->secteur_activite,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'pays' => $request->pays,
            'ville' => $request->ville,
            'coordonnees_gps' => $request->coordonnees_gps,
            'adresse_physique' => $request->adresse_physique,
            'description' => $request->description,
            'liens_reseaux_sociaux' => $request->liens_reseaux_sociaux ? json_encode($request->liens_reseaux_sociaux) : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création',
            'error' => $e->getMessage()
        ], 500);
    }
});