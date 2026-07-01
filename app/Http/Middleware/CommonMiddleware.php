<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CommonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (file_exists(storage_path('installed'))) {
            if (session()->has('local')) {
                Carbon::setLocale(session()->get('local'));
                App::setLocale(session()->get('local'));
            } else {
                $language = Language::where('default', ACTIVE)->first();
                if ($language) {
                    $ln = $language->code;
                    session(['local' => $ln]);
                    Carbon::setLocale(session()->get('local'));
                    App::setLocale(session()->get('local'));
                } else {
                    $language = Language::firstOrFail();
                    if ($language) {
                        $ln = $language->code;
                        session(['local' => $ln]);
                        Carbon::setLocale(session()->get('local'));
                        App::setLocale(session()->get('local'));
                    }
                }
            }
            return $next($request);
        } else {
            return redirect()->to('/install');
        }
    }
}
