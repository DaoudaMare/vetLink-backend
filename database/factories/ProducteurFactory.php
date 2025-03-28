<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Producteur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producteur>
 */
class ProducteurFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Producteur::class;

    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
            }, // Associe à un utilisateur
            'localisation' => $this->faker->city(),
            'notation' => $this->faker->randomFloat(1, 0, 5), // Entre 0.0 et 5.0
            'type_production' => $this->faker->randomElement(['fruits', 'légumes', 'céréales']),
            'certifications' => json_encode($this->faker->randomElements(['Bio', 'Équitable', 'Sans OGM'], rand(0, 3))),
            'mode_paiement' => $this->faker->randomElement(['espèces', 'carte bancaire', 'virement']),
        ];
    }
}
