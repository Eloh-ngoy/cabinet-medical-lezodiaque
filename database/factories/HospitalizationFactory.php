<?php

namespace Database\Factories;

use App\Models\Bed;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class HospitalizationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'bed_id' => Bed::factory(),
            'admission_date' => fake()->dateTime(),
            'expected_duration' => fake()->optional()->numberBetween(1, 30),
            'status' => 'active',
            'admission_reason' => fake()->optional()->sentence(),
        ];
    }
}
