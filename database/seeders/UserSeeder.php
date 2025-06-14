<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'firstName' => 'Admin',
                'lastName' => 'System',
                'email' => 'admin@vetlink.com',
                'tel1' => '0123456789',
                'tel2' => '0987654321',
                'user_type_id' => 1, // Administrateur
                'organization_id' => 1,
                'password' => Hash::make('password123'),
            ],
            [
                'firstName' => 'Jean',
                'lastName' => 'Dupont',
                'email' => 'jean.dupont@fermebio.fr',
                'tel1' => '0234567891',
                'tel2' => '0876543219',
                'user_type_id' => 3, // Producteur
                'organization_id' => 1,
                'password' => Hash::make('password123'),
            ],
            [
                'firstName' => 'Marie',
                'lastName' => 'Martin',
                'email' => 'marie.martin@elevage.fr',
                'tel1' => '0345678912',
                'tel2' => '0765432198',
                'user_type_id' => 3, // Producteur
                'organization_id' => 2,
                'password' => Hash::make('password123'),
            ],

            [
                'firstName' => 'Mare',
                'lastName' => 'Daouda',
                'email' => 'adminvetlink@gmail.com',
                'tel1' => '0123456789',
                'tel2' => '0987654321',
                'user_type_id' => 1, // Administrateur
                'organization_id' => 1,
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
} 