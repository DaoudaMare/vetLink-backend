<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SousSecteur;
use App\Models\Secteur;

class SousSecteurSeeder extends Seeder
{
    public function run()
    {
        $secteurs = Secteur::all();

        $sousSecteurs = [
            ['Agriculture' => [
                ['nom' => 'Céréales', 'code' => 'CER'],
                ['nom' => 'Légumes', 'code' => 'LEG'],
            ]],
            ['Élevage' => [
                ['nom' => 'Bovins', 'code' => 'BOV'],
                ['nom' => 'Volailles', 'code' => 'VOL'],
            ]],
        ];

        foreach ($sousSecteurs as $group) {
            foreach ($group as $secteurNom => $items) {
                $secteur = $secteurs->where('nom', $secteurNom)->first();

                foreach ($items as $item) {
                    SousSecteur::create([
                        'nom' => $item['nom'],
                        'code' => $item['code'],
                        'description' => "Sous-secteur ".$item['nom'],
                        'secteur_id' => $secteur->id,
                    ]);
                }
            }
        }
    }
}
