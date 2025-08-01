<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Produit; // This is the model for 'produits' table

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $produits = Produit::all();

        if ($users->isEmpty() || $produits->isEmpty()) {
            echo "Not enough users or products to create reviews. Please run UserSeeder and ProduitSeeder first.\n";
            return;
        }

        Review::create([
            'user_id' => $users->random()->id,
            'product_id' => $produits->random()->id, // Corrected from 'produit_id' to 'product_id'
            'rating' => 5,
            'comment' => 'Excellent product, highly recommended!',
        ]);

        Review::create([
            'user_id' => $users->random()->id,
            'product_id' => $produits->random()->id, // Corrected from 'produit_id' to 'product_id'
            'rating' => 3,
            'comment' => 'Good product, but delivery was slow.',
        ]);

        Review::create([
            'user_id' => $users->random()->id,
            'product_id' => $produits->random()->id, // Corrected from 'produit_id' to 'product_id'
            'rating' => 4,
            'comment' => 'Very satisfied with the quality.',
        ]);
    }
}