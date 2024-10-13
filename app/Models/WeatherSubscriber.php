<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherSubscriber extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'location', 'token', 'is_confirmed'];
}
