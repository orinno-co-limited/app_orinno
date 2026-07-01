<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class IsDemo
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('APP_DEMO') == true) {
            if ($request->wantsJson()) {
                return $this->error([],  'This is a demo version! You can get full access after purchasing the application.');
            } else {
                return redirect()->back()->with('error', 'This is a demo version! You can get full access after purchasing the application.');
            }
        }
        return $next($request);
    }
}
