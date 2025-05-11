<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secteur>
 */
class SecteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->unique()->word,
            'code' => 'SEC-'.$this->faker->unique()->randomNumber(4),
            'description' => $this->faker->sentence,
        ];
    }
}
