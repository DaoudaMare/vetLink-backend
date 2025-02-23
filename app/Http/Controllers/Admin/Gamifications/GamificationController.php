<?php

namespace App\Http\Controllers\Admin\Gamifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    public function classement()
    {
        return view('gamifications.classement');
    }
    public function defis()
    {
        return view('gamifications.defis');
    }
    public function rewards()
    {
        return view('gamifications.rewards');
    }
}
