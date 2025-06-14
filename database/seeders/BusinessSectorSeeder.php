<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessSector;

class BusinessSectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['name' => 'Agriculture'],
            ['name' => 'Élevage'],
            ['name' => 'Pêche'],
            ['name' => 'Transformation alimentaire'],
            ['name' => 'Distribution'],
            ['name' => 'Services agricoles'],
        ];

        foreach ($sectors as $sector) {
            BusinessSector::create($sector);
        }
    }
} 