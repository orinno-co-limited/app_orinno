<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Meta extends Model
{
    use HasFactory;

    protected $table = 'metas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keyword',
    ];


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
        });
    }


}
