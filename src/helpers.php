<?php

if (! function_exists('settings')) {
    function settings(String $key = null)
    {
        $settings = app()->make(Justijndepover\Settings\Settings::class);

        if (!is_null($key)) {
            return $settings->get($key);
        }

        return $settings;
    }
}
