<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ViewLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return view('auth.login');
    }
}
