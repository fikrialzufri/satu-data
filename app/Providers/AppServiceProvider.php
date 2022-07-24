<?php

namespace App\Providers;

use App\Models\JenisData;
use Illuminate\Support\ServiceProvider;
use App\Models\PersonalAccessToken;
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
        $jenis_data = JenisData::orderBy('nama', 'asc')->get();
        View::share('jenis_data', $jenis_data);
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
