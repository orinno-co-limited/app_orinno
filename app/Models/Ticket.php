<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    public function attachments()
    {
        return $this->hasMany(FileManager::class, 'origin_id', 'id')->where('origin_type', 'App\Models\Ticket');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(TicketTopic::class, 'topic_id', 'id')->select('id', 'name', 'owner_user_id', 'status');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'ticket_id', 'id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->ticket_no = sprintf("%'.08d", $model->id);
            $model->save();
        });
    }
}
