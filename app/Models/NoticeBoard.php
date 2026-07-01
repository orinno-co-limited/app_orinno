<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoticeBoard extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The notice that belong to the NoticeBoard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function userNotices()
    {
        return $this->belongsToMany(User::class, 'notice_user', 'notice_id', 'user_id');
    }

    /**
     * Get the user that owns the NoticeBoard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id', 'id');
    }

    public function getImageAttribute(): string
    {
        if ($this->fileAttach) {
            return $this->fileAttach->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttach()
    {
        return $this->morphOne(FileManager::class, 'origin');
    }
}
