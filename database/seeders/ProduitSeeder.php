<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Organization;
use App\Models\OrganizationType;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques catégories existantes
        $categories = Categorie::all();
        
        // Récupérer les types d'organisation qui peuvent produire
        $typesProducteurs = ['Ferme', 'Élevage', 'Transformation'];
        
        // Récupérer les organisations qui sont des producteurs
        $producteurs = Organization::whereHas('organizationType', function($query) use ($typesProducteurs) {
            $query->whereIn('name', $typesProducteurs);
        })->get();

        if ($categories->isEmpty()) {
            $this->command->error('Veuillez d\'abord exécuter le seeder pour les catégories.');
            return;
        }

        if ($producteurs->isEmpty()) {
            $this->command->error('Aucune organisation de type producteur trouvée. Veuillez exécuter les seeders pour les types d\'organisation et les organisations.');
            return;
        }

        $produits = [
            [
                'name' => 'Pommes Bio',
                'quantity' => 100.5,
                'price' => 2500, // 25.00 €
                'measure' => 'kg',
            ],
            [
                'name' => 'Carottes Fraîches',
                'quantity' => 75.0,
                'price' => 1500, // 15.00 €
                'measure' => 'kg',
            ],
            [
                'name' => 'Lait de Vache',
                'quantity' => 50.0,
                'price' => 200, // 2.00 €
                'measure' => 'L',
            ],
            [
                'name' => 'Œufs Fermiers',
                'quantity' => 120,
                'price' => 500, // 5.00 €
                'measure' => 'unité',
            ],
            [
                'name' => 'Miel Artisanal',
                'quantity' => 25.0,
                'price' => 1500, // 15.00 €
                'measure' => 'kg',
            ],
            [
                'name' => 'Poulet Fermier',
                'quantity' => 30.0,
                'price' => 1200, // 12.00 €
                'measure' => 'kg',
            ],
            [
                'name' => 'Fromage de Chèvre',
                'quantity' => 15.0,
                'price' => 1800, // 18.00 €
                'measure' => 'kg',
            ],
            [
                'name' => 'Pain Artisanal',
                'quantity' => 50,
                'price' => 350, // 3.50 €
                'measure' => 'unité',
            ],
            [
                'name' => 'Tomates Bio',
                'quantity' => 45.0,
                'price' => 300, // 3.00 €
                'measure' => 'kg',
            ],
            [
                'name' => 'Huile d\'Olive Extra Vierge',
                'quantity' => 20.0,
                'price' => 2500, // 25.00 €
                'measure' => 'L',
            ],
        ];

        foreach ($produits as $produit) {
            Produit::create([
                'name' => $produit['name'],
                'categorie_id' => $categories->random()->id,
                'producer_id' => $producteurs->random()->id,
                'quantity' => $produit['quantity'],
                'price' => $produit['price'],
                'measure' => $produit['measure'],
            ]);
        }

        $this->command->info('Produits créés avec succès !');
    }
} 