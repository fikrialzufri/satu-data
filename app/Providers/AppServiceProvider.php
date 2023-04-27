<?php

namespace App\Providers;

use App\Models\JenisData;
use Illuminate\Support\ServiceProvider;
use App\Models\PersonalAccessToken;
use App\Models\Unit;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $jenis_data = JenisData::orderBy('nama', 'asc')->get();
            $unit = Unit::orderBy('nama', 'asc')->get();
            View::share('jenis_data', $jenis_data);
            View::share('unit', $unit);
        } catch (\Throwable $th) {
            //throw $th;
        }
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
