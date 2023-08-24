<?php

namespace Jetcod\IpIntelligence;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Jetcod\IpIntelligence\Console\InstallCommand;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/IpIntelligence.php' => config_path('IpIntelligence.php'),
        ], 'ip-intelligence-config');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/IpIntelligence.php', 'IpIntelligence');

        $this->app->singleton(GeoLite2::class, function () {
            return new GeoLite2(config('IpIntelligence.GeoLite2'));
        });

        $this->app->bind('command.ipintelligence.install', function () {
            return new InstallCommand();
        });

        $this->commands([
            'command.ipintelligence.install',
        ]);
    }
}
