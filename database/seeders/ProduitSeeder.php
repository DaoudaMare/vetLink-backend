<?php
namespace Database\Seeders;

use App\Models\Produit;
use App\Models\Secteur;
use App\Models\SousSecteur;
use App\Models\Activite;
use App\Models\Producteur;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifie et crée les relations nécessaires si elles n'existent pas
        $secteur = Secteur::firstOrCreate(
            ['nom' => 'Agriculture'],
            ['code' => 'AGR', 'description' => 'Secteur agricole']
        );

        $sousSecteur = SousSecteur::firstOrCreate(
            ['nom' => 'Céréales', 'secteur_id' => $secteur->id],
            ['code' => 'CER', 'description' => 'Culture de céréales']
        );

        $activite = Activite::firstOrCreate(
            ['nom' => 'Culture du maïs', 'sous_secteur_id' => $sousSecteur->id],
            ['exemples' => 'Maïs blanc, maïs jaune']
        );

        // Crée un producteur si aucun n'existe
        if (!Producteur::exists()) {
            Producteur::factory()->count(5)->create();
        }

        // Crée les produits avec des relations valides
        Produit::factory()
            ->count(30)
            ->create([
                'secteur_id' => $secteur->id,
                'sous_secteur_id' => $sousSecteur->id,
                'activite_id' => $activite->id,
                'producteur_id' => function () {
                    return Producteur::inRandomOrder()->first()->id;
                }
            ]);
    }
}
