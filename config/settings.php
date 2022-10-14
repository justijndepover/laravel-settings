<?php

return [

    /**
     * This setting determines what driver you want to use
     * You can overwrite this driver with your own custom one.
     */
    'driver' => \Justijndepover\Settings\Drivers\Database::class,

    /**
     * Automatically store the app locale
     * If this settings is enabled, the app locale will always be stored in database
     * omitting the need to scope your result set:
     *
     * settings()->forLocale(app()->getLocale())->get('name') becomes: settings()->get('name')
     */
    'auto_store_locale' => false,

    /**
     * Duration the system should cache the fetched database results
     * possible values: 'forever', (int) $seconds
    */
    'cache_time' => 'forever',

];
