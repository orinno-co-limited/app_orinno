<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceIssue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'owner_user_id', 'status'];
}
