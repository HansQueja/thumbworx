<?php

namespace Database\Factories;

use App\Models\DrugTestResult;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrugTestResultFactory extends Factory
{
    protected $model = DrugTestResult::class;

    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(),
            'test_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'result' => $this->faker->randomElement(['positive', 'negative']),
        ];
    }
}
