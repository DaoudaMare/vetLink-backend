<?php

namespace Database\Factories;

use App\Models\BusinessSector;
use App\Models\OrganizationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organisation>
 */
class OrganisationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'adresse' => $this->faker->address,
            'business_sector_id' => BusinessSector::factory(),
            'organization_type_id' => OrganizationType::factory(),
            'email' => $this->faker->unique()->safeEmail,
            'tel1' => $this->faker->phoneNumber,
            'tel2' => $this->faker->phoneNumber,
        ];
    }
}
