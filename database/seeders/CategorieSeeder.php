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
            ['name' => 'Viandes'],
            ['name' => 'Produits laitiers'],
            ['name' => 'Produits transformés'],
            ['name' => 'Produits bio'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
} 