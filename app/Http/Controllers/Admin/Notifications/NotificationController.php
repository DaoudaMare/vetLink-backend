<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    
    public function listeNotifications()
    {
        return view('notifications.listes_notifications');
    }
    public function parametre()
    {
        return view('notifications.parametres');
    }
}
