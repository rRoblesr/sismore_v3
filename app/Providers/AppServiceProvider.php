<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
        if (!defined('TOKEN_APIPERU')) {
            define('TOKEN_APIPERU', '41ad0f3f2505d2f9b060857d62abdafdba0a1716e4e8ecde51f0327c66acb265');
        }
    }
}
