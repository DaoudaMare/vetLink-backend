<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Légumes'],
            ['name' => 'Fruits'],
            ['name' => 'Céréales'],
            ['name' => 'Viande'],
            ['name' => 'Poisson'],
            ['name' => 'Lait'],
            ['name' => 'Fromage'],
            ['name' => 'Yaourt'],
            ['name' => 'Beurre'],
            ['name' => 'Crème'],
            ['name' => 'Œufs'],
            ['name' => 'Pain'],
            ['name' => 'Miel'],
            ['name' => 'Produits transformés'],
            ['name' => 'Produits bio'],
        ];

        foreach ($categories as $categorie) {
            Categorie::firstOrCreate(['name' => $categorie['name']], $categorie);
        }

        $this->command->info('Catégories créées avec succès!');
    }
} 