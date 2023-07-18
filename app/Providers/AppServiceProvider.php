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
        \Spatie\Flash\Flash::levels([
            'success' => 'bg-emerald-500',
            'warning' => 'bg-yellow-400',
            'error' => 'bg-red-500',
            'info' => 'bg-blue-500'
        ]);
    }
}
