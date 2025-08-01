<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\User;
use App\Models\ProductImage;

class ProduitLocalImagesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Categorie::all();
        $producers = User::whereHas('userType', function ($query) {
            $query->where('title', 'Producteur');
        })->get();

        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Veuillez exécuter CategorieSeeder d\'abord.');
            return;
        }

        if ($producers->isEmpty()) {
            $this->command->error('Aucun producteur trouvé. Veuillez exécuter UserSeeder d\'abord.');
            return;
        }

        $produits = [
            // ... (les données des produits restent les mêmes)
        ];

        foreach ($produits as $produitData) {
            // On ne garde que le nom de l'image pour le fun, mais on utilisera une URL placeholder
            $images = $produitData['images'];
            unset($produitData['images']);

            $produit = Produit::create($produitData);

            foreach ($images as $imageName) {
                $imageUrl = 'https://picsum.photos/seed/' . uniqid() . '/640/480';

                ProductImage::create([
                    'name' => $imageName, // On garde le nom original pour référence
                    'type' => 'image/jpeg', // Type MIME standard pour les images de placeholder
                    'path' => $imageUrl, // On stocke directement l'URL
                    'product_id' => $produit->id
                ]);
            }

            $this->command->info("Produit créé: {$produit->name}");
        }

        $this->command->info('Seeder ProduitLocalImagesSeeder terminé avec succès!');
    }
}
