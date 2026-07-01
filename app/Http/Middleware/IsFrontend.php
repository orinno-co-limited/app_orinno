<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsFrontend
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
        if (isAddonInstalled('PROTYSAAS') > 1) {
            if (getOption('frontend_status', 1) != ACTIVE) {
                return redirect()->route('login');
            }
        }
        return $next($request);
    }
}
