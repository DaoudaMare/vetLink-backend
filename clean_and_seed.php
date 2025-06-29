<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

echo "=== Nettoyage et Seeding Complet ===\n\n";

try {
    // 1. Nettoyer les caches
    echo "1. Nettoyage des caches...\n";
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
    echo "✓ Caches nettoyés\n\n";

    // 2. Vider la base de données (optionnel - décommentez si nécessaire)
    // echo "2. Vidage de la base de données...\n";
    // DB::statement('PRAGMA foreign_keys = OFF;');
    // DB::statement('DELETE FROM users;');
    // DB::statement('DELETE FROM organisations;');
    // DB::statement('DELETE FROM user_types;');
    // DB::statement('DELETE FROM categories;');
    // DB::statement('DELETE FROM produits;');
    // DB::statement('DELETE FROM product_image;');
    // DB::statement('PRAGMA foreign_keys = ON;');
    // echo "✓ Base de données vidée\n\n";

    // 3. Seed des types d'utilisateurs
    echo "3. Création des types d'utilisateurs...\n";
    Artisan::call('db:seed', ['--class' => 'UserTypeSeeder']);
    echo "✓ Types d'utilisateurs créés\n\n";

    // 4. Seed des types d'organisations
    echo "4. Création des types d'organisations...\n";
    Artisan::call('db:seed', ['--class' => 'OrganizationTypeSeeder']);
    echo "✓ Types d'organisations créés\n\n";

    // 5. Seed des secteurs d'activité
    echo "5. Création des secteurs d'activité...\n";
    Artisan::call('db:seed', ['--class' => 'BusinessSectorSeeder']);
    echo "✓ Secteurs d'activité créés\n\n";

    // 6. Seed des catégories
    echo "6. Création des catégories...\n";
    Artisan::call('db:seed', ['--class' => 'CategorieSeeder']);
    echo "✓ Catégories créées\n\n";

    // 7. Seed des statuts
    echo "7. Création des statuts...\n";
    Artisan::call('db:seed', ['--class' => 'StatusSeeder']);
    echo "✓ Statuts créés\n\n";

    // 8. Seed des organisations
    echo "8. Création des organisations...\n";
    Artisan::call('db:seed', ['--class' => 'OrganizationSeeder']);
    echo "✓ Organisations créées\n\n";

    // 9. Seed des utilisateurs
    echo "9. Création des utilisateurs...\n";
    Artisan::call('db:seed', ['--class' => 'UserSeeder']);
    echo "✓ Utilisateurs créés\n\n";

    // 10. Seed des produits avec images
    echo "10. Création des produits avec images...\n";
    Artisan::call('db:seed', ['--class' => 'ProduitSeeder']);
    echo "✓ Produits créés avec images\n\n";

    echo "=== Seeding terminé avec succès! ===\n";
    echo "🎉 Votre application est prête!\n\n";
    echo "📋 Informations de connexion:\n";
    echo "   Admin: admin@vetlink.com / password123\n";
    echo "   Admin 2: adminvetlink@gmail.com / password\n";
    echo "   Producteur: jean.dupont@fermebio.fr / password123\n";
    echo "   Producteur: marie.martin@elevage.fr / password123\n\n";
    echo "🌐 Accès:\n";
    echo "   - Panel Admin: http://localhost:8000/admin\n";
    echo "   - API: http://localhost:8000/api\n";

} catch (Exception $e) {
    echo "❌ Erreur lors du seeding: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 