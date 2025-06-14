<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'En attente'],
            ['name' => 'En cours'],
            ['name' => 'Validé'],
            ['name' => 'Annulé'],
            ['name' => 'Terminé'],
            ['name' => 'En litige'],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
} 