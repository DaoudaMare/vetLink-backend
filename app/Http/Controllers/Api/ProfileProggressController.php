<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfileProgress;
use App\Repositories\ProfileProgressRepository;

class ProfileProggressController extends Controller
{
    protected $profileProgressRepository;

    public function __construct(ProfileProgressRepository $profileProgressRepository)
    {
        $this->profileProgressRepository = $profileProgressRepository;
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id)
    {
        $user = $this->profileProgressRepository->showUserProfile($user_id);
        if (!$user) {
            return response()->json([
                'message' => 'Profile Utilisateur non trouvé'
            ], 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $this->profileProgressRepository->showUserProfile($id);
        dd($user);
        
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // $this->profileProgressRepository->updateProfileProgress($user->profileProgress, $request->all());
        // dd($user->toArray());
        ProfileProgress::updateProgress($user);

        return response()->json([
            'message' => 'Profil Utilisateur mis à jour avec succès',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
