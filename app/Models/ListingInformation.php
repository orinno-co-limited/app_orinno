<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListingInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_user_id',
        'listing_id',
        'name',
        'distance',
        'contact_number',
        'details',
        'file_id',
    ];
}
