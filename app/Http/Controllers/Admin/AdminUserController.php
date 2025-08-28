<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use App\Models\Organization;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs avec filtres
     */
    public function index(Request $request)
    {
        $query = User::with(['userType', 'organisation']);
        
        // Filtres
        if ($request->has('user_type_id')) {
            $query->where('user_type_id', $request->user_type_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->latest()->paginate(20);
        $userTypes = UserType::all();
        
        return view('admin.users.index', compact('users', 'userTypes'));
    }
    
    /**
     * Afficher les détails d'un utilisateur
     */
    public function show(User $user)
    {
        $user->load(['userType', 'organisation', 'documents', 'produits', 'reviews']);
        
        $stats = [
            'products_count' => $user->produits()->count(),
            'orders_count' => $user->commandes()->count(),
            'reviews_count' => $user->reviews()->count(),
            'documents_count' => $user->documents()->count(),
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }
    
    /**
     * Formulaire de création d'utilisateur
     */
    public function create()
    {
        $userTypes = UserType::all();
        $organizations = Organization::all();
        
        return view('admin.users.create', compact('userTypes', 'organizations'));
    }
    
    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::defaults()],
            'user_type_id' => 'required|exists:user_types,id',
            'organisation_id' => 'nullable|exists:organisations,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type_id' => $request->user_type_id,
            'organisation_id' => $request->organisation_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }
    
    /**
     * Formulaire d'édition d'utilisateur
     */
    public function edit(User $user)
    {
        $userTypes = UserType::all();
        $organizations = Organization::all();
        
        return view('admin.users.edit', compact('user', 'userTypes', 'organizations'));
    }
    
    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type_id' => 'required|exists:user_types,id',
            'organisation_id' => 'nullable|exists:organisations,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,suspended',
        ]);
        
        $user->update($request->only([
            'firstName', 'lastName', 'email', 'user_type_id', 
            'organisation_id', 'phone', 'address', 'status'
        ]));
        
        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès');
    }
    
    /**
     * Suspendre un utilisateur
     */
    public function suspend(User $user)
    {
        $user->update(['status' => 'suspended']);
        
        return redirect()->back()
            ->with('success', 'Utilisateur suspendu avec succès');
    }
    
    /**
     * Activer un utilisateur
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'active']);
        
        return redirect()->back()
            ->with('success', 'Utilisateur activé avec succès');
    }
    
    /**
     * Supprimer un utilisateur (soft delete)
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }
    
    /**
     * Vérifier le profil d'un utilisateur
     */
    public function verifyProfile(User $user)
    {
        $user->update(['profile_verified_at' => now()]);
        
        return redirect()->back()
            ->with('success', 'Profil vérifié avec succès');
    }
    
    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->back()
            ->with('success', 'Mot de passe réinitialisé avec succès');
    }
}
