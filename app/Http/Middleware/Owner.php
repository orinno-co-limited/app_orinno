<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Owner
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->role != USER_ROLE_OWNER && Auth::user()->role != USER_ROLE_TEAM_MEMBER) {
            if ($request->wantsJson()) {
                $message = __("Unauthorized");
                return $this->error([], $message);
            } else {
                abort('403');
            }
        }
        return $next($request);
    }
}
