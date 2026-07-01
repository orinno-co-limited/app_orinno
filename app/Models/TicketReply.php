<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['ticket_id','user_id','reply'];

    public function attachments()
    {
        return $this->hasMany(FileManager::class, 'origin_id', 'id')->where('origin_type', 'App\Models\TicketReply');
    }
}
