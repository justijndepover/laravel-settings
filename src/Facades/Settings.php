<?php

namespace Justijndepover\Settings\Facades;

use Illuminate\Support\Facades\Facade;

class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Justijndepover\Settings\Settings';
    }
}
