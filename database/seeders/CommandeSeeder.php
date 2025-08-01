<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommandeSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('userType', function ($query) {
            $query->where('title', 'Client');
        })->get();

        $produits = Produit::all();

        if ($customers->isEmpty() || $produits->isEmpty()) {
            $this->command->info('Cannot seed commandes without customers and produits.');
            return;
        }

        for ($i = 0; $i < 20; $i++) { // Create 20 orders
            $customer = $customers->random();
            $numberOfProducts = rand(1, 3);
            $selectedProducts = $produits->random($numberOfProducts);

            $totalPrice = 0;
            $productsToAttach = [];

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 5);
                $totalPrice += $product->price * $quantity;
                $productsToAttach[$product->id] = ['quantity' => $quantity];
            }

            $commande = Commande::create([
                'num' => 'CMD-' . strtoupper(uniqid()),
                'customer_id' => $customer->id,
                'total_price' => $totalPrice,
                'status' => rand(1, 5), // Assuming status IDs from 1 to 5
                'delivery_status' => rand(1, 5),
                'payment' => rand(0, 1),
            ]);

            $commande->produits()->attach($productsToAttach);
        }
    }
}