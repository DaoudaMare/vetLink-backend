<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserType;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le type d'utilisateur Admin s'il n'existe pas
        $adminType = UserType::firstOrCreate(
            ['title' => 'Admin'],
            ['title' => 'Admin']
        );

        // Créer l'organisation Admin si elle n'existe pas
        $adminOrg = Organization::firstOrCreate(
            ['name' => 'VetLink Admin'],
            [
                'name' => 'VetLink Admin',
                'adresse' => 'Siège social',
                'email' => 'admin@vetlink.com',
                'tel1' => '0000000000',
                'business_sector_id' => 1,
                'organization_type_id' => 1
            ]
        );

        // Créer l'utilisateur admin
        User::firstOrCreate(
            ['email' => 'admin@vetlink.com'],
            [
                'firstName' => 'Admin',
                'lastName' => 'System',
                'email' => 'admin@vetlink.com',
                'tel1' => '0000000000',
                'password' => Hash::make('password'),
                'user_type_id' => $adminType->id,
                'organization_id' => $adminOrg->id,
            ]
        );
    }
}