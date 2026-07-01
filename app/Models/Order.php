<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['payment_id', 'transaction_id', 'user_id', 'invoice_id', 'amount', 'tax_amount', 'tax_percentage', 'system_currency', 'gateway_id', 'gateway_currency', 'conversion_rate', 'subtotal', 'total', 'transaction_amount', 'payment_status', 'bank_id', 'bank_name', 'bank_account_number', 'deposit_by', 'deposit_slip_id'];
}
