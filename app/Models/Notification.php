<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'url',
        'image',
        'is_seen',
        'user_id',
        'sender_id',


    ];

    public function sender()
    {
        return $this->belongsTo(user::class, 'sender_id');
    }




    public function user()
    {
        return $this->belongsTo(user::class);
    }

}
