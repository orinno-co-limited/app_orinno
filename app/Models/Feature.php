<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
          'title',
          'summary',
          'status'
    ];

    public function getIconAttribute(): string
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
}
