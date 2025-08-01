<?php 
namespace App\Repositories;

use App\Models\User;
use App\Models\Organization;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserInterface
{
     /**
        * Inscription d'un utilisateur
     */
    public function register(array $data)
    {
        // Hasher le mot de passe
        $data['password'] = Hash::make($data['password']);

        // Si l'utilisateur est un producteur, créer l'organisation d'abord
        if (isset($data['user_type_id']) && $data['user_type_id'] == 3) { // Assuming 3 is the ID for 'Producteur'
            $organization = Organization::create([
                'name' => $data['organization_name'],
                'organization_type_id' => $data['organization_type_id'],
                'business_sector_id' => $data['business_sector_id'],
                'adresse' => $data['organization_address'],
                'tel1' => $data['organization_tel1'], // Ajouté
                'tel2' => $data['organization_tel2'] ?? null, // Ajouté, nullable
            ]);
            $data['organization_id'] = $organization->id;

            // Supprimer les données de l'organisation du tableau de l'utilisateur
            unset($data['organization_name']);
            unset($data['organization_type_id']);
            unset($data['business_sector_id']);
            unset($data['organization_address']);
        }

        // Créer et retourner l'utilisateur
        return User::create($data);
    }

    /**
     * Authentification d'un utilisateur
     */
    public function inLogin(array $credentials)
    {
        
        // 1️⃣ Récupérer l'utilisateur via l'email
        $user = User::where('email', $credentials['email'])->first();

        // 2️⃣ Vérifier si l'utilisateur existe
        if (!$user) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // 3️⃣ Vérifier si le mot de passe est correct
        if (!Hash::check($credentials['password'], $user->password)) {       
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        // 4️⃣ Générer un token (si tu utilises Laravel Sanctum ou Passport)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5️⃣ Retourner la réponse avec le token
        return response()->json([
            'message' => 'Authentification réussie',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Récupérer le profil utilisateur connecté
     */
    public function profile()
    {
        return Auth::user();
    }

    /**
     * Retourne tous les utilisateurs
     */
    public function getAll($perPage = 15)
    {
        return User::paginate($perPage);
    }

    /**
     * Retourne un utilisateur par son ID
     */
    public function findById($id)
    {
        return User::find($id);
    }


    /**
     * Mise à jour d'un utilisateur
     */
    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }

    /**
     * Suppression d'un utilisateur
     */
    public function delete(User $user)
    {
        return $user->delete();
    }
}