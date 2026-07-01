<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        self::created(function ($model) {
            $model->invoice_no = $model->name . '-' . sprintf("%'.08d", $model->id);
            $model->save();
        });
    }
}
