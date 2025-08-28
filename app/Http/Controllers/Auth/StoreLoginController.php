<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginUserRequest;

class StoreLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => "L'email ou le mot de passe n'est pas valide.",
        ])->onlyInput('email');
    }
}
