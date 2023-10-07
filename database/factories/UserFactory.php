<?php

namespace Database\Factories;

use Bluemmb\Faker\PicsumPhotosProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition(): array
    {
        $faker = \Faker\Factory::create('pt_BR');
        $faker->addProvider(new PicsumPhotosProvider($faker));
        return [
            'email'         => $faker->unique()->safeEmail(),
            'password'      => bcrypt('password'),
            'username'      => $faker->unique()->userName(),
            'name'          => $faker->name(),
            'avatar'        => $faker->imageUrl(640, 480, true),
            'is_admin'      => rand(0, 1),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
