<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'license_number' => strtoupper($this->faker->bothify('???-####')),
            'birthdate' => $this->faker->date('Y-m-d', '-21 years'),
            'contact' => $this->faker->numerify('09##-###-####'),
        ];
    }
}
