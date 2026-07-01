<?php

namespace App\Http\Middleware;

use App\Models\Tenancy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class TenancyMiddleware
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
        if (isAddonInstalled('PROTYTENANCY') > 0) {
            if (auth()->check()) {
                $authUser = auth()->user();
                if ($authUser->role == USER_ROLE_TENANT || $authUser->role == USER_ROLE_MAINTAINER || $authUser->role == USER_ROLE_OWNER) {
                    $domainExisting = request()->getHost();
                    $tenancy = Tenancy::query()
                        ->join('domains', 'tenancies.id', '=', 'domains.tenant_id')
                        ->where('domains.domain', $domainExisting)
                        ->first();
                    if (!is_null($tenancy)) {
                        if ($authUser->role == USER_ROLE_OWNER) {
                            if ($tenancy->owner_user_id == $authUser->id) {
                                return $next($request);
                            }
                        } else {
                            return $next($request);
                        }
                        auth()->logout();
                        return redirect("login")->with('error',  __('Email or password is incorrect'));
                    }
                }
            }
        }
        return $next($request);
    }
}
