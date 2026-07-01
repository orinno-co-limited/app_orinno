<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'transaction_id',
        'user_id',
        'package_id',
        'duration_type',
        'amount',
        'tax_amount',
        'tax_percentage',
        'system_currency',
        'gateway_id',
        'gateway_currency',
        'conversion_rate',
        'subtotal',
        'total',
        'transaction_amount',
        'payment_status',
        'bank_id',
        'bank_name',
        'bank_account_number',
        'deposit_by',
        'deposit_slip_id',
        'quantity',
        'package_type',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }
}
