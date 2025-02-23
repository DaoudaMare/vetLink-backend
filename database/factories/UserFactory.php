<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\TypeUserEnum;
use Illuminate\Support\Str;
use App\Enums\TypeSecteurActiviteEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'nom_raison_sociale' => $this->faker->name,
            'type_user' => $this->faker->randomElement(array_column(TypeUserEnum::cases(), 'value')),
            'secteur_activite' => $this->faker->randomElement(array_column(TypeSecteurActiviteEnum::cases(), 'value')),
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->unique()->phoneNumber,
            'pays' => $this->faker->country,
            'ville' => $this->faker->city,
            'coordonnees_gps' => $this->faker->latitude . ',' . $this->faker->longitude,
            'adresse_physique' => $this->faker->address,
            'photo_profil' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph,
            'password' => bcrypt('password'), // Mot de passe par défaut
            'liens_reseaux_sociaux' => json_encode([
                'facebook' => $this->faker->url,
                'twitter' => $this->faker->url,
            ]),
            'user_id' => null, // Vous pouvez ajuster cela si nécessaire
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'type_user' => TypeUserEnum::Admin->value,
            ];
        });
    }

    public function moderateur()
    {
        return $this->state(function (array $attributes) {
            return [
                'type_user' => TypeUserEnum::Moderateur->value,
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
