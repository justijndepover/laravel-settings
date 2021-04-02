<?php

if (! function_exists('settings')) {
    function settings(string $key = null, string $default = null)
    {
        $settings = app()->make(Justijndepover\Settings\Settings::class);

        if (!is_null($key)) {
            return $settings->get($key, $default);
        }

        return $settings;
    }
}
