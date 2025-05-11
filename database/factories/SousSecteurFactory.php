<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SousSecteur>
 */
class SousSecteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->word,
            'code' => 'SS-'.$this->faker->unique()->randomNumber(4),
            'description' => $this->faker->paragraph,
            'secteur_id' => \App\Models\Secteur::factory(),
        ];
    }
}
