<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseBackupController extends Controller
{
    public function database_backup()
    {
        Artisan::call('database:backup');
        return redirect()->back()->with('success', 'Database Backup Successfully.');
    }

    public function database_list()
    {
        $data['pageTitle'] = 'Database Backup';
        $data['navDatabaseActiveClass'] = 'mm-active';
        $file_path = storage_path() . "/app/public/database/";
        if (!file_exists($file_path)) {
            mkdir($file_path, 0770, true);
        }
        $data['database'] = File::allFiles(storage_path('app/public/database')) ?? [];
        return view('admin.database.list', $data);
    }

    public function database_download($file)
    {
        $path = storage_path('app/public/database/') . $file;
        return response()->download($path);
    }

    public function database_delete($file)
    {
        $path = storage_path('app/public/database/') . $file;
        unlink($path);
        return redirect()->back()->with('success', 'Database Backup Deleted Successfully.');
    }
}
