<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraccarController;
use App\Http\Controllers\DriverController;

// For Position/Map
Route::get('/traccar/devices', [TraccarController::class, 'devices']);
Route::get('/traccar/positions', [TraccarController::class, 'positions']);

// For Driver profile
Route::get('/drivers/{id}/profile', [DriverController::class, 'profile']);
Route::get('/drivers', [DriverController::class, 'driver_list']);