<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\User;
use App\Models\productImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProduitSeederFixed extends Seeder
{
    public function run(): void
    {
        // Récupérer les catégories existantes
        $categories = Categorie::all();
        $producers = User::where('user_type_id', 3)->get(); // Producteurs

        if ($categories->isEmpty()) {
            $this->command->error('Aucune catégorie trouvée. Veuillez exécuter CategorieSeeder d\'abord.');
            return;
        }

        if ($producers->isEmpty()) {
            $this->command->error('Aucun producteur trouvé. Veuillez exécuter UserSeeder d\'abord.');
            return;
        }

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
                'images' => [
                    'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1506976785307-8732e854ad03?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a?w=500&h=500&fit=crop'
                ]
            ],
            [
                'name' => 'Carottes Bio Fraîches',
                'description' => 'Carottes bio fraîchement récoltées, riches en bêta-carotène.',
                'categorie_id' => $categories->where('name', 'Légumes')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 60,
                'price' => 2.90,
                'measure' => 'kg',
                'isbio' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1447175008436-170170753d52?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1585478259715-876acc5be8eb?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=500&h=500&fit=crop'
                ]
            ],
            [
                'name' => 'Poulet Fermier Bio',
                'description' => 'Poulet fermier élevé en plein air, nourri aux céréales bio.',
                'categorie_id' => $categories->where('name', 'Viande')->first()?->id ?? $categories->first()->id,
                'producer_id' => $producers->first()->id,
                'quantity' => 15,
                'price' => 18.90,
                'measure' => 'kg',
                'isbio' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1604503468506-a8da13d82791?w=500&h=500&fit=crop'
                ]
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
                'images' => [
                    'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&h=500&fit=crop',
                    'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=500&h=500&fit=crop'
                ]
            ]
        ];

        foreach ($produits as $produitData) {
            // Extraire les images du tableau de données
            $images = $produitData['images'];
            unset($produitData['images']);

            // Créer le produit
            $produit = Produit::create($produitData);

            // Télécharger et associer les images
            foreach ($images as $imageUrl) {
                try {
                    $response = Http::timeout(10)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                        ->retry(3, 1000)
                        ->get($imageUrl);
                    if ($response->successful()) {
                        $imageName = 'produit_' . $produit->id . '_' . uniqid() . '.jpg';
                        $imagePath = 'produits/' . $imageName;
                        
                        // Stocker l'image
                        Storage::disk('public')->put($imagePath, $response->body());
                        
                        // Créer l'enregistrement dans la base de données
                        productImage::create([
                            'name' => $imageName,
                            'type' => 'image/jpeg',
                            'path' => $imagePath,
                            'product_id' => $produit->id
                        ]);
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Impossible de télécharger l'image: $imageUrl");
                }
            }

            $this->command->info("Produit créé: {$produit->name}");
        }

        $this->command->info('Seeder ProduitSeederFixed terminé avec succès!');
    }
} 