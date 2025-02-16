<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée 20 utilisateurs aléatoires
        User::factory()->count(20)->create();
        
         // Crée un admin spécifique
         User::factory()->create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'type_utilisateur' => 'admin',
            'abonnement' => true,
        ]);
    }
}
