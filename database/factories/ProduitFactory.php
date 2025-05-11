<?php

namespace Database\Factories;

use App\Models\Activite;
use App\Models\Producteur;
use App\Models\Produit;
use App\Models\Secteur;
use App\Models\SousSecteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produit>
 */
class ProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
        'nom_produit' => $this->faker->word,
        'description' => $this->faker->sentence,
        'prix' => $this->faker->numberBetween(100, 10000),
        'quantite_disponible' => $this->faker->numberBetween(1, 100),
        // Les clés étrangères seront fournies par le seeder
        'code_type' => $this->faker->optional()->word,
        'unite_mesure' => $this->faker->randomElement(['kg', 'g', 'L', 'unité']),
        'image_principale' => 'products/'.$this->faker->uuid.'.jpg',
        'images_secondaires' => json_encode([
            'products/'.$this->faker->uuid.'.jpg',
            'products/'.$this->faker->uuid.'.jpg'
        ])
    ];
    }
}
