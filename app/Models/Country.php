<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_name',
        'country_name',
        'flag',
        'slug',
        'phonecode',
        'continent',
    ];
}
