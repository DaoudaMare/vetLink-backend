<?php

namespace Database\Factories;

use App\Enums\TypeSecteurActiviteEnum;
use App\Models\Producteur;
use App\Models\User;
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
    {return [
            'user_id' => function () {
                return User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
            },
            'type_entite' => $this->faker->randomElement(['particulier', 'association', 'entreprise', 'startup']),
            'notation' => $this->faker->randomFloat(1, 0, 5),
            'secteur_activite' => $this->faker->randomElement(array_column(TypeSecteurActiviteEnum::cases(), 'value')),
            'type_production' => $this->faker->randomElement(['fruits', 'légumes', 'céréales']),
            'mode_paiement' => $this->faker->randomElement(['espèces', 'carte bancaire', 'virement']),
            'liens_reseaux_sociaux' => [
                'facebook' => $this->faker->url(),
                'twitter' => $this->faker->url(),
                'instagram' => $this->faker->url(),
            ],
            'description' => $this->faker->paragraph(),
        ];
    }
}
