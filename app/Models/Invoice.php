<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['late_fee'];

    public function scopePending($query)
    {
        return $query->whereStatus(INVOICE_STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->whereStatus(INVOICE_STATUS_PAID);
    }

    public function scopeOverDue($query)
    {
        return $query->whereStatus(INVOICE_STATUS_OVER_DUE);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function propertyUnit(): BelongsTo
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            // invoice_no is NOT NULL/unique but depends on the auto-increment id, which
            // doesn't exist yet here - give it a placeholder so the initial insert succeeds
            // on databases that enforce NOT NULL strictly (the created event below then
            // overwrites it with the real id-based value right after).
            if (empty($model->invoice_no)) {
                $model->invoice_no = 'TEMP-' . Str::uuid();
            }
        });
        self::created(function ($model) {
            $model->invoice_no = $model->name . '-' . sprintf("%'.08d", $model->id);
            $model->save();
        });
    }
}
