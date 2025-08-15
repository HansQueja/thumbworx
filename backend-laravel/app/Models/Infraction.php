<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'incident',
        'description',
        'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
