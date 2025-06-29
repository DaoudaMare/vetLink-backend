<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;

// 1. S'assurer qu'il existe un type Admin
$adminType = UserType::where('title', 'Admin')->first();
if (!$adminType) {
    $adminType = UserType::create(['title' => 'Admin']);
    echo "Type Admin créé.\n";
}

// 2. Corriger l'utilisateur admin principal
$email = 'admin@vetlink.com';
$admin = User::where('email', $email)->first();
if ($admin) {
    $admin->user_type_id = $adminType->id;
    if (!Hash::check('password123', $admin->password)) {
        $admin->password = Hash::make('password123');
    }
    $admin->save();
    echo "Utilisateur $email corrigé (type=Admin, mot de passe=password123).\n";
} else {
    // Créer l'utilisateur admin si absent
    User::create([
        'firstName' => 'Admin',
        'lastName' => 'System',
        'email' => $email,
        'tel1' => '0123456789',
        'tel2' => '0987654321',
        'user_type_id' => $adminType->id,
        'organization_id' => 1,
        'password' => Hash::make('password123'),
    ]);
    echo "Utilisateur $email créé (type=Admin, mot de passe=password123).\n";
}

// 3. Corriger tous les autres utilisateurs pour s'assurer que leur mot de passe est hashé
$users = User::all();
foreach ($users as $user) {
    if (strlen($user->password) < 40) { // Probablement pas hashé
        $user->password = Hash::make($user->password);
        $user->save();
        echo "Mot de passe hashé pour: {$user->email}\n";
    }
}

echo "\nCorrection terminée. Essayez de vous connecter sur /admin/login avec admin@vetlink.com / password123\n"; 