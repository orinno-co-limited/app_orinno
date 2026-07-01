<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeActive($query)
    {
        return $query->whereStatus(ACTIVE);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'property_id', 'id');
    }

    public function maintainers()
    {
        return $this->hasMany(Maintainer::class, 'property_id', 'id');
    }

    public function propertyDetail(): HasOne
    {
        return $this->hasOne(PropertyDetail::class);
    }

    public function propertyImages(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function propertyUnits(): HasMany
    {
        return $this->hasMany(PropertyUnit::class, 'property_id', 'id')->select('id', 'unit_name', 'property_id');
    }

    public function getThumbnailImageAttribute()
    {
        if ($this->fileAttachThumbnail) {
            return $this->fileAttachThumbnail->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttachThumbnail()
    {
        return $this->hasOne(FileManager::class, 'id', 'thumbnail_image_id')->select('id', 'folder_name', 'file_name', 'origin_type', 'origin_id');
    }
}
