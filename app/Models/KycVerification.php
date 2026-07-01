<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class KycVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['tenant_id', 'kyc_config_id', 'type', 'front_id', 'back_id'];

    public function config(): BelongsTo
    {
        return $this->belongsTo(KycConfig::class, 'kyc_config_id', 'id');
    }

    public function getFrontAttribute()
    {
        if ($this->fileAttachFront) {
            return $this->fileAttachFront->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttachFront()
    {
        return $this->hasOne(FileManager::class, 'id', 'front_id')->select('id', 'folder_name', 'file_name', 'origin_type', 'origin_id');
    }

    public function getBackAttribute()
    {
        if ($this->fileAttachBack) {
            return $this->fileAttachBack->FileUrl;
        }
        return asset('assets/images/no-image.jpg');
    }

    public function fileAttachBack()
    {
        return $this->hasOne(FileManager::class, 'id', 'back_id')->select('id', 'folder_name', 'file_name', 'origin_type', 'origin_id');
    }
}
