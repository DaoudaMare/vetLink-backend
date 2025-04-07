<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Commande;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commande>
 */
class CommandeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Commande::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date_commande' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'statut' => $this->faker->randomElement(['en attente', 'en cours', 'livrée', 'annulée']),
        ];
    }
}
