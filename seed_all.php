<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "=== Démarrage du seeding complet ===\n\n";

try {
    // 1. Seed des types d'utilisateurs
    echo "1. Création des types d'utilisateurs...\n";
    Artisan::call('db:seed', ['--class' => 'UserTypeSeeder']);
    echo "✓ Types d'utilisateurs créés\n\n";

    // 2. Seed des utilisateurs
    echo "2. Création des utilisateurs...\n";
    Artisan::call('db:seed', ['--class' => 'UserSeeder']);
    echo "✓ Utilisateurs créés\n\n";

    // 3. Seed des catégories
    echo "3. Création des catégories...\n";
    Artisan::call('db:seed', ['--class' => 'CategorieSeeder']);
    echo "✓ Catégories créées\n\n";

    // 4. Seed des produits avec images
    echo "4. Création des produits avec images...\n";
    Artisan::call('db:seed', ['--class' => 'ProduitSeeder']);
    echo "✓ Produits créés avec images\n\n";

    echo "=== Seeding terminé avec succès! ===\n";
    echo "Vous pouvez maintenant accéder à votre application.\n";
    echo "Admin: admin@vetlink.com / password123\n";

} catch (Exception $e) {
    echo "❌ Erreur lors du seeding: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 