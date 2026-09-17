<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    public function propertyUnits(): BelongsToMany
    {
        return $this->belongsToMany(PropertyUnit::class, 'property_unit_amenity');
    }
}
