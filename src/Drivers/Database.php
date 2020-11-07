<?php

namespace Justijndepover\Settings\Drivers;

use Justijndepover\Settings\Settings;
use Illuminate\Support\Facades\DB;

class Database implements Settings
{
    protected $values;

    public function all()
    {
        $this->fetchSettings();

        return $this->values->mapWithKeys(function ($value) {
            return [$value->key => $value->value];
        });
    }

    public function get(String $key, String $default = '') : String
    {
        $this->fetchSettings();

        $setting = $this->values->where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public function set($key, String $value = null) : void
    {
        $valuesToUpdate = (! is_array($key)) ? [$key => $value] : $key;
        $this->fetchSettings();

        foreach ($valuesToUpdate as $k => $v) {
            $setting = $this->values->where('key', $k)->first();
            $freshRecord = true;

            if (! is_null($setting)) {
                $freshRecord = false;
            } else {
                $setting = new \stdClass();
            }

            $setting->key = $k;
            $setting->value = $v;
            $setting->updated_at = date('Y-m-d H:i:s');

            if ($freshRecord) {
                $setting->created_at = date('Y-m-d H:i:s');
                $id = DB::table('settings')->insertGetId((array) $setting);
                $setting->id = $id;
                $this->values->add($setting);
            } else {
                DB::table('settings')->where('id', '=', $setting->id)->update((array) $setting);
            }
        }
    }

    public function has(String $key) : bool
    {
        $this->fetchSettings();

        $setting = $this->values->where('key', $key)->first();

        return $setting ? true : false;
    }

    public function flush() : void
    {
        DB::table('settings')->delete();
        $this->values = null;
    }

    public function delete(String $key = null) : void
    {
        if (is_null($key)) {
            $this->flush();
        }

        $this->forget($key);
    }

    public function forget(String $key) : void
    {
        $this->fetchSettings();
        $value = $this->values->where('key', $key)->first();

        if (! $value) {
            return;
        }

        DB::table('settings')->where('key', '=', $key)->delete();

        $index = $this->values->search(function ($item) use ($key) {
            return $item->key == $key;
        });

        if ($index !== false) {
            $this->values->forget($index);
        }
    }

    private function fetchSettings()
    {
        if (empty($this->values)) {
            $this->values = DB::table('settings')->get();
        }
    }
}