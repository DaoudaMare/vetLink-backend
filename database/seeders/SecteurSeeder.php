<?php

namespace Database\Seeders;


use App\Models\Secteur;
use Illuminate\Database\Seeder;

class SecteurSeeder extends Seeder
{
    public function run(): void
    {
        Secteur::insert([
            [
                'nom' => 'Élevage',
                'code' => 'ELE',
                'description' => 'Activités relatives à la production animale'
            ],
            [
                'nom' => 'Agriculture',
                'code' => 'AGR',
                'description' => 'Culture des végétaux et production végétale'
            ],
            [
                'nom' => 'Pêche',
                'code' => 'PEC',
                'description' => 'Activités halieutiques et aquacoles'
            ],
        ]);
    }
}
