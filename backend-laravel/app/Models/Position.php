<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'driver_id',
        'device_id',
        'latitude',
        'longitude',
        'speed',
        'device_time',
        'attributes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attributes'  => 'array',      // Stores JSON attributes as array automatically
        'device_time' => 'datetime',   // Converts to Carbon instance
        'latitude'    => 'float',      // Ensures numeric type
        'longitude'   => 'float',      // Ensures numeric type
        'speed'       => 'float',      // Ensures numeric type
        'device_id'   => 'integer',    // Ensures integer type
        'driver_id'   => 'integer',
    ];

    /**
     * Define relationship: a position belongs to a driver via device_id.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
