<?php

namespace Justijndepover\Settings;

use Illuminate\Support\ServiceProvider;
use Justijndepover\Settings\Drivers\Database;
use Justijndepover\Settings\Settings;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'settings');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/settings.php' => config_path('settings.php'),
            ], 'laravel-settings-config');

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }

        if (config('settings.driver') == 'database') {
            $this->app->singleton(Settings::class, Database::class);
        }
    }
}