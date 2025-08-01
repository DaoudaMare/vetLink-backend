<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Organisation;
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
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'categorie_id' => Categorie::factory(),
            'producer_id' => User::factory(),
            'organisation_id' => Organisation::factory(),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'price' => $this->faker->numberBetween(1, 1000),
            'measure' => $this->faker->randomElement(['kg', 'g', 'L', 'unité']),
            'isbio' => $this->faker->boolean,
        ];
    }
}
