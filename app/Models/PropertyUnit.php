<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyUnit extends Model
{
    use HasFactory, SoftDeletes;

    public function propertyUnits(): HasMany
    {
        return $this->hasMany(PropertyUnit::class);
    }

    public function amenityList(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_unit_amenity');
    }

    /**
     * Get the tenant that owns the PropertyUnit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activeTenant(): HasOne
    {
        return $this->hasOne(Tenant::class, 'unit_id', 'id')->where('status', TENANT_STATUS_ACTIVE);
    }
}
