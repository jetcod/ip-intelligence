<?php

namespace Jetcod\IpIntelligence;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/IpIntelligence.php' => config_path('IpIntelligence.php'),
        ], 'ip-intelligence');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/IpIntelligence.php', 'IpIntelligence');

        $this->app->singleton(GeoLite2::class, function () {
            return new GeoLite2(config('IpIntelligence.GeoLite2'));
        });
    }
}
