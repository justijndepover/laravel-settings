<?php

namespace Justijndepover\Settings;

interface Settings
{
    public function all();

    public function get(String $key, String $default = '') : String;

    public function set($key, String $value = null) : void;

    public function has(String $key) : bool;

    public function flush() : void;

    public function delete(String $key = null) : void;

    public function forget(String $key) : void;
}