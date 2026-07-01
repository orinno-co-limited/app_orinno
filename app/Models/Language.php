<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'icon',
        'rtl',
        'status',
        'default',
        'font_id'
    ];

    public function getIconAttribute()
    {
        if ($this->fileAttach) {
            return $this->fileAttach->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttach()
    {
        return $this->morphOne(FileManager::class, 'origin')->select('id', 'folder_name', 'file_name', 'origin_type', 'origin_id');
    }

    public function getFontAttribute()
    {
        if ($this->fileAttachFont) {
            return $this->fileAttachFont->FileUrl;
        }
        return '';
    }

    public function fileAttachFont()
    {
        return $this->hasOne(FileManager::class, 'id', 'font_id')->select('id', 'folder_name', 'file_name', 'origin_type', 'origin_id');
    }
}
