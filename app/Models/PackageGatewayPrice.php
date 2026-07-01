<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageGatewayPrice extends Model
{
    protected $fillable = [
        'package_id',
        'gateway_id',
        'gateway_currency_id',
        'gateway',
        'monthly_price_id',
        'yearly_price_id',
        'status',
    ];
}
