<?php

namespace Database\Factories;

use App\Faker\Providers\KenyaProvider;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->withFaker();
        $faker->addProvider(new KenyaProvider($faker));
        return [
            'organization_id' => Organization::query()->inRandomOrder()->first()->id,
            'name' => $faker->company(),
            'address' => $faker->address(),
            'town' => $faker->randomElement(['Nairobi', 'Kisumu', 'Mombasa']),
            'county' => $faker->randomElement(['Nairobi', 'Kisumu', 'Mombasa']),
            'type' => $faker->randomElement(['residential', 'commercial']),
            'units' => $faker->randomNumber(2),
            'is_visible' => true,
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $faker->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
