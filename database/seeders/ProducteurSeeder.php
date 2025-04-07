<?php

namespace Database\Seeders;

use App\Models\Producteur;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProducteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Producteur::factory()->count(10)->create(); // Créer 10 producteurs
    }
}
