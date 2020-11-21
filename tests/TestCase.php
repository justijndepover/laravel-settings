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

        include_once __DIR__.'/../database/migrations/2020_11_07_170322_create_settings_table.php';
        (new \CreateSettingsTable())->up();
    }
}
