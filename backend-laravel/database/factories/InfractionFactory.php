<?php

namespace Database\Factories;

use App\Models\Infraction;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfractionFactory extends Factory
{
    protected $model = Infraction::class;

    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(),
            'incident' => $this->faker->randomElement(['Late Delivery', 'Unprofessional Behavior', 'Route Deviation']),
            'description' => $this->faker->sentence(),
            'date' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
