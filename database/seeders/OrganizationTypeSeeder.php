<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationType;

class OrganizationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Ferme',
                'product_name' => 'Produits agricoles'
            ],
            [
                'name' => 'Élevage',
                'product_name' => 'Produits d\'élevage'
            ],
            [
                'name' => 'Transformation',
                'product_name' => 'Produits transformés'
            ],
            [
                'name' => 'Distribution',
                'product_name' => 'Services de distribution'
            ],
        ];

        foreach ($types as $type) {
            OrganizationType::create($type);
        }
    }
} 