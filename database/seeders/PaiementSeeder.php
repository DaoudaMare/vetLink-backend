<?php

namespace Database\Seeders;

use App\Models\Paiement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Paiement::factory()->count(10)->create(); // Créer 10 paiements
    }
}
