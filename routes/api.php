<?php

use App\Models\User;
use Illuminate\Support\Str;
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



Route::post('/register', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'nom_raison_sociale' => 'required|string|max:255',
        'type_user' => 'required|string|max:255',
        'secteur_activite' => 'nullable|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'telephone' => 'required|string|max:255|unique:users',
        'pays' => 'required|string|max:255',
        'ville' => 'nullable|string|max:255',
        'coordonnees_gps' => 'nullable|string|max:255',
        'adresse_physique' => 'nullable|string|max:255',
        'photo_profil' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'liens_reseaux_sociaux' => 'nullable|array',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $user = User::create([
        'id' => Str::uuid(),
        'nom_raison_sociale' => $request->nom_raison_sociale,
        'type_user' => $request->type_user,
        'secteur_activite' => $request->secteur_activite,
        'email' => $request->email,
        'telephone' => $request->telephone,
        'pays' => $request->pays,
        'ville' => $request->ville,
        'coordonnees_gps' => $request->coordonnees_gps,
        'adresse_physique' => $request->adresse_physique,
        'photo_profil' => $request->photo_profil,
        'description' => $request->description,
        'liens_reseaux_sociaux' => $request->liens_reseaux_sociaux ? json_encode($request->liens_reseaux_sociaux) : null,
        'password' => Hash::make($request->password),
    ]);

    return response()->json(['user' => $user], 201);
});
