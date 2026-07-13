<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'telephone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'date_naissance' => fake()->date(),
            'sexe' => fake()->randomElement(['homme', 'femme']),
            'groupe_sanguin' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'statut_interne_externe' => fake()->randomElement(['interne', 'externe']),
            'traitement_passe' => fake()->optional()->sentence(),
            'adresse' => fake()->optional()->address(),
            'contact_urgence_nom' => fake()->optional()->name(),
            'contact_urgence_telephone' => fake()->optional()->phoneNumber(),
        ];
    }
}
