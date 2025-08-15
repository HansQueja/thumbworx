<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'license_number',
        'birthdate',
        'contact',
        'device_id'
    ];

    // Driver has many DrugTestResults
    public function drugTestResults()
    {
        return $this->hasMany(DrugTestResult::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function infractions()
    {
        return $this->hasMany(Infraction::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
