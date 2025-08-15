<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Driver;

class DriverController extends Controller
{
    public function profile($id)
    {
        $driver = Driver::with(['drugTestResults', 'violations', 'feedback', 'credentials', 'infractions'])->findOrFail($id);
        return response()->json($driver);
    }

    public function driver_list()
    {
        $driver_list = Driver::select('id', 'name', 'license_number', 'device_id')->get();
        return response()->json($driver_list);
    }
}
