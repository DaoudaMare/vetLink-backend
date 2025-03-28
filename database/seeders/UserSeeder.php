<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un admin
        $admin = User::factory()->admin()->create([
            'nom_raison_sociale' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

         // Créer 100 utilisateurs avec d'autres types
         User::factory()->count(10)->create();
    }
}
