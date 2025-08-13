<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TraccarController;

Route::get('/traccar/devices', [TraccarController::class, 'devices']);
Route::get('/traccar/positions', [TraccarController::class, 'positions']);
