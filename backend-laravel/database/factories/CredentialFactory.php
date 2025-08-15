<?php

namespace Database\Factories;

use App\Models\Credential;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

class CredentialFactory extends Factory
{
    protected $model = Credential::class;

    public function definition(): array
    {
        return [
            'driver_id' => Driver::factory(), // Automatically creates a related driver
            'type' => $this->faker->randomElement([
                'Driver License',
                'NBI Clearance',
                'Barangay Clearance',
                'Police Clearance',
                'Medical Certificate',
            ]),
            'document_path' => 'uploads/' . $this->faker->uuid . '.pdf', // Simulated upload
            'is_valid' => $this->faker->boolean(80), // 80% chance it's valid
            'remarks' => $this->faker->optional()->sentence(),
        ];
    }
}
