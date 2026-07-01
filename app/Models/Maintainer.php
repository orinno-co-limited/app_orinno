<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintainer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the Maintainer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function property(): HasOne
    {
        return $this->hasOne(Property::class, 'id', 'property_id');
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'maintainer_id', 'user_id');
    }
}
