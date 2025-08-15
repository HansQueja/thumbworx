<?php

namespace Database\Factories;

use App\Models\Violation;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViolationFactory extends Factory
{
    protected $model = Violation::class;

    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(),
            'type' => $this->faker->randomElement(['Speeding', 'Illegal Parking', 'Reckless Driving']),
            'description' => $this->faker->sentence(),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
