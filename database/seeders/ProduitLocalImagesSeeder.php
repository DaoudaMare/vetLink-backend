<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\User;
use App\Models\ProductImage; // Correction du nom de classe
use Illuminate\Support\Facades\Storage;

class ProduitLocalImagesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Categorie::all();
        $producers = User::where('user_type_id', 3)->get();

        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Veuillez exécuter CategorieSeeder d\'abord.');
            return;
        }

        if ($producers->isEmpty()) {
            $this->command->error('Aucun producteur trouvé. Veuillez exécuter UserSeeder d\'abord.');
            return;
        }

        // Chemin de base pour tes images locales
        $baseImagePath = 'C:/Users/daoud/Downloads/';

        $produits = [
            [
                'name' => 'Lait Bio Frais',
                'description' => 'Lait de vache bio frais, riche en calcium et protéines.',
                'categorie_id' => $categories->where('name', 'Lait')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 50,
                'price' => 2.50,
                'measure' => 'L',
                'isbio' => true,
                'images' => ['lait.jpeg', 'lait1.jpeg']
            ],
            [
                'name' => 'Fromage de Chèvre Artisanal',
                'description' => 'Fromage de chèvre affiné 3 mois, goût délicat et texture onctueuse.',
                'categorie_id' => $categories->where('name', 'Fromage')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 25,
                'price' => 8.90,
                'measure' => 'unité',
                'isbio' => true,
                'images' => ['fromage.jpeg']
            ],
            [
                'name' => 'Œufs Bio Fermiers',
                'description' => 'Œufs frais de poules élevées en plein air, nourries aux céréales bio.',
                'categorie_id' => $categories->where('name', 'Œufs')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 100,
                'price' => 4.20,
                'measure' => 'unité',
                'isbio' => true,
                'images' => ['oeuf.jpeg', 'oeuf1.jpeg']
            ],
            [
                'name' => 'Miel de Fleurs Sauvages',
                'description' => 'Miel pur récolté par nos abeilles dans les prairies sauvages.',
                'categorie_id' => $categories->where('name', 'Miel')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 30,
                'price' => 12.50,
                'measure' => 'g',
                'isbio' => true,
                'images' => ['miel.jpeg']
            ],
            [
                'name' => 'Pommes Golden Bio',
                'description' => 'Pommes Golden cultivées sans pesticides, croquantes et sucrées.',
                'categorie_id' => $categories->where('name', 'Fruits')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 80,
                'price' => 3.80,
                'measure' => 'kg',
                'isbio' => true,
                'images' => ['pomme.jpeg', 'pomme1.jpeg']
            ],
            [
                'name' => 'Pastèque Bio Fraîche',
                'description' => 'Pastèque bio juteuse et sucrée, parfaite pour l\'été.',
                'categorie_id' => $categories->where('name', 'Fruits')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 20,
                'price' => 6.50,
                'measure' => 'kg',
                'isbio' => true,
                'images' => ['pasteque.jpeg']
            ],
            [
                'name' => 'Pain Complet Artisanal',
                'description' => 'Pain complet cuit au feu de bois, farine de blé bio moulue à la meule de pierre.',
                'categorie_id' => $categories->where('name', 'Pain')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 40,
                'price' => 3.50,
                'measure' => 'unité',
                'isbio' => true,
                'images' => ['pain.jpeg']
            ],
            [
                'name' => 'Yaourt Nature Bio',
                'description' => 'Yaourt nature bio au lait entier, fermenté naturellement.',
                'categorie_id' => $categories->where('name', 'Yaourt')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 70,
                'price' => 1.80,
                'measure' => 'unité',
                'isbio' => true,
                'images' => ['yaourt.jpeg']
            ],
            [
                'name' => 'Viande de Bœuf Bio',
                'description' => 'Viande de bœuf bio de race Limousine, élevé en pâturage.',
                'categorie_id' => $categories->where('name', 'Viande')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 25,
                'price' => 28.50,
                'measure' => 'kg',
                'isbio' => true,
                'images' => ['viande.jpeg']
            ],
            [
                'name' => 'Saumon Fumé Artisanal',
                'description' => 'Saumon fumé artisanal, fumé au bois de hêtre.',
                'categorie_id' => $categories->where('name', 'Poisson')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 20,
                'price' => 24.50,
                'measure' => 'g',
                'isbio' => false,
                'images' => ['saumon.jpeg']
            ]
        ];

        foreach ($produits as $produitData) {
            $images = $produitData['images'];
            unset($produitData['images']);

            $produit = Produit::create($produitData);

            foreach ($images as $fileName) {
                $imagePath = $baseImagePath . $fileName;

                try {
                    if (file_exists($imagePath)) {
                        $imageName = 'produit_' . $produit->id . '_' . uniqid() . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
                        $newImagePath = 'produits/' . $imageName;

                        $imageContent = file_get_contents($imagePath);
                        Storage::disk('public')->put($newImagePath, $imageContent);

                        ProductImage::create([
                            'name' => $imageName,
                            'type' => mime_content_type($imagePath),
                            'path' => $newImagePath,
                            'product_id' => $produit->id
                        ]);

                        $this->command->info("Image copiée: $imagePath -> $newImagePath");
                    } else {
                        $this->command->warn("Image non trouvée: $imagePath");
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Erreur lors de la copie de l'image: $imagePath - " . $e->getMessage());
                }
            }

            $this->command->info("Produit créé: {$produit->name}");
        }

        $this->command->info('Seeder ProduitLocalImagesSeeder terminé avec succès!');
    }
}
