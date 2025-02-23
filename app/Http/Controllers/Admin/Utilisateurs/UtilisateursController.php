<?php

namespace App\Http\Controllers\Admin\Utilisateurs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilisateursController extends Controller
{
    public function listeUtilisateurs()
    {
        return view('utilisateurs.listes_utilisateurs');
    }

    public function verificationProfile()
    {
        return view('utilisateurs.verification_profile');
    }
}
