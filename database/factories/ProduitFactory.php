<?php

namespace Database\Factories;

use App\Models\Produit;
use App\Models\Producteur;
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
            'producteur_id' => Producteur::factory(),
            'nom_produit' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'prix' => $this->faker->numberBetween(100, 5000),
            'quantite_disponible' => $this->faker->numberBetween(1, 100),
        ];
    }
}
