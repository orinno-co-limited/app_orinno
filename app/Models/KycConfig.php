<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KycConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'tenant_id', 'details', 'status', 'owner_user_id', 'is_both'];

    public function getImageAttribute(): string
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
}
