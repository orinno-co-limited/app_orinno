<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class VersionUpdate
{

    public function handle(Request $request, Closure $next)
    {
        $codeBuildVersion = config('app.build_version');
        $dbBuildVersion = getCustomerCurrentBuildVersion();

        if ($codeBuildVersion > $dbBuildVersion) {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Auth::logout();
            if (!file_exists(storage_path('installed'))) {
                return redirect()->to('/install');
            }
            return redirect()->route('version-update');

        }
        return $next($request);
    }
}
