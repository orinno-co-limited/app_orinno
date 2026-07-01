<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function subscriptionPrice()
    {
        return $this->hasMany(PackageGatewayPrice::class, 'package_id', 'id');
    }
}
