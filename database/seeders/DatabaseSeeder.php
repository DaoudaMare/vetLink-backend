<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProduitSeeder;
use Database\Seeders\CommandeSeeder;
use Database\Seeders\PaiementSeeder;
use Database\Seeders\ProducteurSeeder;
use Database\Seeders\ProfileProgressSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UserSeeder::class,
            ProfileProgressSeeder::class
            // ProducteurSeeder::class,
            // ProduitSeeder::class,
            // CommandeSeeder::class,
            // PaiementSeeder::class,
        ]);
    }
}
// php artisan db:seed --class=ProfileProgressSeeder
