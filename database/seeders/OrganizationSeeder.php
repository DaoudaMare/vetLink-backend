<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'Ferme Bio du Soleil',
                'adresse' => '123 Route des Champs, 75000 Paris',
                'business_sector_id' => 1,
                'organization_type_id' => 1,
                'email' => 'contact@fermebio.fr',
                'tel1' => '0123456789',
                'tel2' => '0987654321',
            ],
            [
                'name' => 'Élevage Traditionnel',
                'adresse' => '456 Chemin des Vaches, 69000 Lyon',
                'business_sector_id' => 2,
                'organization_type_id' => 2,
                'email' => 'contact@elevage-traditionnel.fr',
                'tel1' => '0234567891',
                'tel2' => '0876543219',
            ],
        ];

        foreach ($organizations as $organization) {
            Organization::firstOrCreate(['email' => $organization['email']], $organization);
        }

        $this->command->info('Organisations créées avec succès!');
    }
} 