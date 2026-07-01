<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_id',
        'total_amount',
        'txn_id',
        'payment_method',
        'currency',
        'payment_details',
        'payment_time',
        'status',
    ];

    function user(){
        return $this->belongsTo(User::class);
    }

    function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
