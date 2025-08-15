<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Driver;
use App\Models\DrugTestResult;
use App\Models\Violation;
use App\Models\Feedback;
use App\Models\Infraction;
use App\Models\Credential;
use App\Models\Position;
use Illuminate\Support\Str;

class DriverFromDevicesSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Get devices from API
        $base = config('services.traccar.flask_api');
        $response = Http::get("{$base}/api/traccar/devices");

        if ($response->failed()) {
            $this->command->error('Failed to fetch devices.');
            return;
        }

        $devices = $response->json();

        foreach ($devices as $device) {
            $deviceId = $device['id'] ?? null;
            $deviceName = $device['name'] ?? fake()->name;

            if (!$deviceId) {
                $this->command->warn('Skipping device due to missing ID.');
                continue;
            }

            // 2️⃣ Create driver with all required fields
            $driver = Driver::factory()->create([
                'device_id' => $deviceId,
                'name' => $deviceName,
            ]);

            // 3️⃣ Seed related tables
            DrugTestResult::factory(rand(1, 3))->create(['driver_id' => $driver->id]);
            Violation::factory(rand(1, 5))->create(['driver_id' => $driver->id]);
            Feedback::factory(rand(2, 6))->create(['driver_id' => $driver->id]);
            Infraction::factory(rand(1, 3))->create(['driver_id' => $driver->id]);
            Credential::factory(rand(1, 2))->create(['driver_id' => $driver->id]);

        }

        $this->command->info('Drivers, related data, and positions seeded successfully.');
    }
}
