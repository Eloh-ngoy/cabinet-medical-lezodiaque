<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BedFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bed_number' => fake()->unique()->numberBetween(100, 999),
            'bed_type' => fake()->randomElement(['standard', 'électrique', 'soins intensifs']),
            'room_number' => fake()->numberBetween(1, 50),
            'is_available' => true,
        ];
    }
}
