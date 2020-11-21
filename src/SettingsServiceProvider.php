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

            if (! class_exists('CreateSettingsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_settings_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_settings_table.php'),
                ], 'laravel-settings-migration');
            }
        }

        if (config('settings.driver') == 'database') {
            $this->app->singleton(Settings::class, Database::class);
        }
    }
}