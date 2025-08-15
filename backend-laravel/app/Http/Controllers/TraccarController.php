<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Driver;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TraccarController extends Controller
{
    public function devices()
    {
        $base = config('services.traccar.flask_api');
        $res = Http::get("{$base}/api/traccar/devices");

        return response()->json($res->json());
    }

    public function positions()
	{
		$base = config('services.traccar.flask_api');
		$positions = Http::get("{$base}/api/positions_cached")->json();

		if (!$positions) {
			return response()->json(['error' => 'Failed to fetch positions'], 500);
		}

		$inserted = 0;
		$skipped  = 0;

		foreach ($positions as $pos) {
			$deviceId = $pos['device_id'] ?? null;
			if (!$deviceId) { $skipped++; continue; }

			$driver = Driver::where('device_id', $deviceId)->first();
			if (!$driver) { $skipped++; continue; }

			$last = Position::where('driver_id', $driver->id)
							->orderByDesc('device_time')
							->first();

			$moved = !$last ||
					$last->latitude  != $pos['latitude']  ||
					$last->longitude != $pos['longitude'] ||
					$last->speed     != $pos['speed'];

			if ($moved) {
				Position::create([
					'driver_id'   => $driver->id,
					'device_id'   => $deviceId,
					'latitude'    => $pos['latitude'] ?? null,
					'longitude'   => $pos['longitude'] ?? null,
					'speed'       => $pos['speed'] ?? null,
					'device_time' => Carbon::parse($pos['device_time']),
					'attributes'  => $pos['attributes'] ?? null,
				]);
				$inserted++;
			} else {
				$skipped++;
			}
		}

		return response()->json($positions);
	}

}
