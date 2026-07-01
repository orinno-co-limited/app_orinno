<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'details',
        'meta_keyword',
        'publish_date',
        'blog_category_id',
        'status',
    ];

    public function getImageAttribute(): string
    {
        if ($this->fileAttach) {
            return $this->fileAttach->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttach()
    {
        return $this->morphOne(FileManager::class, 'origin');
    }

    public function blogCategory(){

        return $this->belongsTo(BlogCategory::class);
    }
}
