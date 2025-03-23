<?php 
namespace App\Repositories;

use App\Models\User;
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

        // Créer et retourner l'utilisateur
        return User::create($data);
    }

    /**
     * Authentification d'un utilisateur
     */
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        return Auth::user()->createToken('API Token')->plainTextToken;
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
    public function getAll()
    {
        return User::all();
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