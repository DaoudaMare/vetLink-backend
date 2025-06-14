<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['title' => 'Administrateur'],
            ['title' => 'Modérateur'],
            ['title' => 'Producteur'],
            ['title' => 'Client'],
        ];

        foreach ($types as $type) {
            UserType::create($type);
        }
    }
} 