<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\UserType;

echo "=== Test d'authentification Filament ===\n\n";

// Vérifier les types d'utilisateurs
echo "Types d'utilisateurs dans la DB:\n";
$userTypes = UserType::all();
foreach ($userTypes as $type) {
    echo "- ID: {$type->id}, Title: {$type->title}\n";
}

echo "\nUtilisateurs dans la DB:\n";
$users = User::with('userType')->get();
foreach ($users as $user) {
    echo "- ID: {$user->id}, Nom: {$user->firstName} {$user->lastName}, Email: {$user->email}, Type: " . ($user->userType ? $user->userType->title : 'NULL') . "\n";
}

// Test avec un utilisateur admin
$adminUser = User::with('userType')->where('email', 'admin@vetlink.com')->first();
if ($adminUser) {
    echo "\nTest avec admin@vetlink.com:\n";
    echo "- isAdmin(): " . ($adminUser->isAdmin() ? 'true' : 'false') . "\n";
    echo "- canAccessPanel(): " . ($adminUser->canAccessPanel(null) ? 'true' : 'false') . "\n";
} else {
    echo "\nUtilisateur admin@vetlink.com non trouvé!\n";
}

echo "\n=== Fin du test ===\n"; 