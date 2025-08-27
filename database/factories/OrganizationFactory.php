<?php

namespace Database\Factories;

use App\Faker\Providers\KenyaProvider;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
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
            'user_id' => User::query()->role(['Landlord', 'Manager'])->inRandomOrder()->first()->id,
            'name' => $faker->company(),
            'phone' => $faker->unique()->kenyanPhone(),
            'email' => $faker->unique()->safeEmail(),
            'address' => $faker->address(),
            'town' => $faker->randomElement(['Nairobi', 'Kisumu', 'Mombasa']),
            'county' => $faker->randomElement(['Nairobi', 'Kisumu', 'Mombasa']),
            'is_visible' => true,
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $faker->dateTimeBetween('-5 months', 'now'),
        ];
    }
}
