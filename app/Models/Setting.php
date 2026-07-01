<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $appends = ['image'];

    protected $fillable = [
        'option_key',
        'option_value',
    ];

    public function getImageAttribute(){
        if ($this->fileAttach){
            return $this->fileAttach->FileUrl;
        }
        return asset('assets/images/no-image.jpg');

    }

    public function fileAttach()
    {
        return $this->morphOne(FileManager::class, 'origin');
    }
}
