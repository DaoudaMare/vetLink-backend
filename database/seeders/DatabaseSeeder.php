<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            OrganizationTypeSeeder::class,
            BusinessSectorSeeder::class,
            CategorieSeeder::class,
            StatusSeeder::class,
            OrganizationSeeder::class,
            UserSeeder::class,
            ProduitSeeder::class,
        ]);
    }
} 