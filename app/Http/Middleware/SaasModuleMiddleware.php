<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SaasModuleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $addons = ['PROTYSAAS', 'PROTYSMS', 'PROTYAGREEMENT', 'PROTYLISTING'];

        foreach ($addons as $addon) {
            $codeBuildVersion = getAddonCodeBuildVersion($addon);
            $dbBuildVersion = getCustomerAddonBuildVersion($addon);
            if ($codeBuildVersion > $dbBuildVersion) {
                Artisan::call('view:clear');
                Artisan::call('route:clear');
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
                if (auth()->check()) {
                    if (auth()->user()->role == USER_ROLE_ADMIN) {
                        return redirect()->route('admin.addon.details', $addon);
                    } else {
                        auth()->logout();
                        return redirect()->route('login')->with('error', __('Please contact with admin'));
                    }
                } else {
                    return redirect()->route('login')->with('error', __('Please contact with admin'));
                }
            }
        }
        return $next($request);
    }
}
