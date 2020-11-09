<?php

namespace Justijndepover\Settings\Tests;

use Justijndepover\Settings\SettingsServiceProvider;
use Orchestra\Testbench\TestCase;

class InitialTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    /** @test */
    public function it_asserts_true()
    {
        $this->assertTrue(true);
    }
}