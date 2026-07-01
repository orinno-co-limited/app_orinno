<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        try {
            if(!env('APP_DEBUG')){
                URL::forceScheme('https');
            }
            Builder::defaultStringLength(191);
            $connection = DB::connection()->getPdo();
            if ($connection) {
                $allOptions = [];
                $allOptions['settings'] = Setting::all()->pluck('option_value', 'option_key')->toArray();
                config($allOptions);
                config(['app.name' => getOption('app_name')]);
            }
            Gate::before(function ($user, $ability) {
                return $user->role == USER_ROLE_TEAM_MEMBER ? false : true;
            });
        } catch (\Exception $e) {
            //
        }
    }
}
