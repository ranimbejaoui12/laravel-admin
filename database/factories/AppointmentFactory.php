<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Patient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date' => fake()->date('Y-m-d'),
            'motivation' => fake()->paragraph(),

            // الأفضل ما نعتمدوش على numbers ثابتة
            'patient_id' => Patient::inRandomOrder()->first()->id ?? 1,

            // user (secretary/admin)
            'user_id' => User::inRandomOrder()->first()->id ?? 1,

            // 🔥 IMPORTANT FIX
            'doctor_id' => User::where('role', 0)->inRandomOrder()->first()->id ?? 1,

            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
        ];
    }
}
