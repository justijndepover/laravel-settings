<?php

namespace Justijndepover\Settings\Tests;

use Justijndepover\Settings\SettingsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $settings;

    public function setUp(): void
    {
        parent::setUp();

        $this->settings = app()->make(\Justijndepover\Settings\Settings::class);
        $this->settings->clearCache();
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Settings' => \Justijndepover\Settings\Facades\Settings::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/../database/migrations/create_settings_table.php';
        (new \CreateSettingsTable())->up();

        include_once __DIR__.'/migrations/2014_10_12_000000_create_users_table.php';
        (new \CreateUsersTable())->up();
    }
}
