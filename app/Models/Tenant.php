<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * Get the user associated with the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function property(): HasOne
    {
        return $this->hasOne(Property::class, 'id', 'property_id');
    }

    public function unit(): HasOne
    {
        return $this->hasOne(PropertyUnit::class, 'id', 'unit_id');
    }

    /**
     * Get the details that owns the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function details(): BelongsTo
    {
        return $this->belongsTo(TenantDetails::class, 'id', 'tenant_id');
    }

    public function documents()
    {
        return $this->hasMany(FileManager::class, 'origin_id', 'id')->where('origin_type', 'App\Models\Tenant');
    }

    public function getImageAttribute()
    {
        if ($this->fileAttachImage) {
            return $this->fileAttachImage->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttachImage()
    {
        return $this->hasOne(FileManager::class, 'id', 'image_id');
    }
}
