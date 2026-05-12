<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'noSSocial' => fake()->numberBetween(10, 99),
            'dob' => fake()->date('Y-m-d'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'diseases' => fake()->sentence(),
            'allergies' => fake()->sentence(),
            'antecedents' => fake()->sentence(),
            'comments' => fake()->paragraph(),

            // 🔥 IMPORTANT FIX
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
