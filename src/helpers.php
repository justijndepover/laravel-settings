<?php

if (! function_exists('settings')) {
    function settings()
    {
        return app()->make(Justijndepover\Settings\Settings::class);
    }
}