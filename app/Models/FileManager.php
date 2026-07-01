<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FileManager extends Model
{
    use HasFactory, SoftDeletes;

    public function upload($to, $file, $name = '')
    {
        try {
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            $mime_type = mime_content_type($file->getPathname());
            if (!in_array($mime_type, allowMimes()) || !in_array($extension, allowExtensions())) {
                throw new Exception('Invalid File');
            }

            if ($name == '') {
                $file_name = mt_rand(100000, 999999) . time() . '.' . $extension;
            } else {
                $file_name = $name . '-' . mt_rand(100000, 999999) . time() . '.' . $extension;
            }

            $file_name = str_replace(' ', '_', $file_name);

            Storage::disk(config('app.STORAGE_DRIVER'))
                ->put('files/' . $to . '/' . $file_name, file_get_contents($file->getRealPath()));

            $store = new self();
            $store->folder_name = 'files/' . $to;
            $store->file_name = $file_name;
            $store->save();

            if (config('app.STORAGE_DRIVER') == 'public') {
                if (!env('IS_SYMLINK_SUPPORT', true)) {
                    copyFolder(storage_path('app/public'), public_path() . "/storage/");
                }
            }
            return ['status' => true, 'file' => $store, 'message' => "File Save Successfully"];
        } catch (\Exception $exception) {
            return ['status' => false, 'file' => [], 'message' => $exception->getMessage()];
        }
    }

    public function updateUpload($id, $to, $file, $name = '')
    {

        try {

            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);

            $mime_type = mime_content_type($file->getPathname());
            if (!in_array($mime_type, allowMimes()) || !in_array($extension, allowExtensions())) {
                throw new Exception('Invalid File');
            }

            if ($name == '') {
                $file_name = mt_rand(100000, 999999) . time() . '.' . $extension;
            } else {
                $file_name = $name . '-' . mt_rand(100000, 999999) . time() . '.' . $extension;
            }

            $file_name = str_replace(' ', '_', $file_name);

            Storage::disk(config('app.STORAGE_DRIVER'))
                ->put('files/' . $to . '/' . $file_name, file_get_contents($file->getRealPath()));

            $store = FileManager::find($id);
            $store->folder_name = 'files/' . $to;
            $store->file_name = $file_name;
            $store->save();
            if (config('app.STORAGE_DRIVER') == 'public') {
                if (!env('IS_SYMLINK_SUPPORT', true)) {
                    copyFolder(storage_path('app/public'), public_path() . "/storage/");
                }
            }
            return ['status' => true, 'file' => $store, 'message' => "File Save Successfully"];
        } catch (\Exception $exception) {
            return ['status' => false, 'file' => [], 'message' => $exception->getMessage()];
        }
    }


    public function  getFileUrlAttribute()
    {
        $destinationPath = $this->folder_name . '/' . $this->file_name;
        if (Storage::disk(config('app.STORAGE_DRIVER'))->exists($this->FileDir)) {
            if (config('app.STORAGE_DRIVER') != "public") {
                $s3 = Storage::disk(config('app.STORAGE_DRIVER'));
                return $s3->url($destinationPath);
            }
            return asset('storage/' . $destinationPath);
        }
        return asset('assets/images/no-image.jpg');
    }

    public function  getFileDirAttribute()
    {
        $destinationPath = $this->folder_name . '/' . $this->file_name;
        return $destinationPath;
    }

    public function removeFile()
    {
        $destinationPath = $this->folder_name . '/' . $this->file_name;
        if (Storage::disk(config('app.STORAGE_DRIVER'))->exists($this->FileDir)) {
            Storage::disk(config('app.STORAGE_DRIVER'))->delete($destinationPath);
            return 100;
        }
        return 200;
    }

    public function origin()
    {
        return $this->morphTo();
    }

    protected $hidden = [
        'origin_type',
    ];
}
