<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activite;
use App\Models\SousSecteur;

class ActiviteSeeder extends Seeder
{
    public function run()
    {
        $sousSecteurs = SousSecteur::all();

        $activites = [
            'Céréales' => [
                ['nom' => 'Culture de maïs'],
                ['nom' => 'Culture de blé'],
            ],
            'Légumes' => [
                ['nom' => 'Culture de tomates'],
                ['nom' => 'Culture de carottes'],
            ],
            'Bovins' => [
                ['nom' => 'Élevage laitier'],
                ['nom' => 'Élevage de boucherie'],
            ],
        ];

        foreach ($activites as $sousSecteurNom => $items) {
            $sousSecteur = $sousSecteurs->where('nom', $sousSecteurNom)->first();

            foreach ($items as $item) {
                Activite::create([
                    'nom' => $item['nom'],
                    'exemples' => "Exemples d'activités pour ".$item['nom'],
                    'sous_secteur_id' => $sousSecteur->id,
                ]);
            }
        }
    }
}
