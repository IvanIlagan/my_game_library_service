<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerOwnedGameFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => random_int(0, 10),
            'review' => fake()->text(),
            'is_finished' => fake()->boolean(),
            'times_played' => random_int(0, 10)
        ];
    }
}