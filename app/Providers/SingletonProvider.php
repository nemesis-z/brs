<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SingletonProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Classes\Helper', function() {
            return new \App\Classes\Helper();
        });
    }

    public function provides() {
        return ['App\Classes\Helper'];
    }
}
