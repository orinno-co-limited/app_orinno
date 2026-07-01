<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgreementHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'is_test',
        'bulk_envelope_status',
        'envelope_id',
        'error_details',
        'recipient_signing_uri',
        'recipient_signing_uri_error',
        'status',
        'status_date_time',
        'uri',
    ];
}
