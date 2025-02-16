<?php

namespace Database\Factories;

use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paiement>
 */
class PaiementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'commande_id' => Commande::factory(),
            'montant' => $this->faker->numberBetween(500, 10000),
            'date_paiement' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'mode_paiement' => $this->faker->randomElement(['carte bancaire', 'espèces', 'virement']),
        ];
    }
}
