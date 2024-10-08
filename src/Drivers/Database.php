<?php

namespace Justijndepover\Settings\Drivers;

use Exception;
use Illuminate\Support\Collection;
use Justijndepover\Settings\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Database implements Settings
{
    protected $values;
    protected $userId = null;
    protected $locale = null;

    public function all(): Collection
    {
        $this->fetchSettings();

        return $this->values->where('user_id', $this->userId)->where('locale', $this->locale)->mapWithKeys(function ($value) {
            return [$value->key => $value->value];
        });

        $this->resetScopes();
    }

    public function get(string $key, string $default = null): ?string
    {
        $this->fetchSettings();

        $setting = $this->values
            ->where('key', $key)
            ->where('user_id', $this->userId)
            ->where('locale', $this->locale)
            ->first();

        $this->resetScopes();

        return ($setting && isset($setting->value)) ? $setting->value : $default;
    }

    public function set($key, string $value = null): void
    {
        $valuesToUpdate = (! is_array($key)) ? [$key => $value] : $key;
        $this->fetchSettings();

        foreach ($valuesToUpdate as $k => $v) {
            $setting = $this->values->where('key', $k)->where('user_id', $this->userId)->where('locale', $this->locale)->first();
            $freshRecord = true;

            if (! is_null($setting)) {
                $freshRecord = false;
            } else {
                $setting = new \stdClass();
            }

            $setting->user_id = $this->userId;
            $setting->locale = $this->locale;
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

        $this->clearCache();
        $this->resetScopes();
    }

    public function has(string $key): bool
    {
        $this->fetchSettings();

        $setting = $this->values
            ->where('key', $key)
            ->where('user_id', $this->userId)
            ->where('locale', $this->locale)
            ->first();

        $this->resetScopes();

        return ($setting && isset($setting->value)) ? true : false;
    }

    public function flush(): void
    {
        DB::table('settings')->delete();
        $this->clearCache();
        $this->values = null;
        $this->resetScopes();
    }

    public function delete(string $key = null): void
    {
        if (is_null($key)) {
            $this->flush();
            return;
        }

        $this->forget($key);
    }

    public function forget(string $key): void
    {
        $this->fetchSettings();
        $value = $this->values
            ->where('key', $key)
            ->where('user_id', $this->userId)
            ->where('locale', $this->locale)
            ->first();

        if (! $value) {
            $this->resetScopes();
            return;
        }

        DB::table('settings')
            ->where('key', '=', $key)
            ->where('user_id', '=', $this->userId)
            ->where('locale', '=', $this->locale)
            ->delete();

        $this->clearCache();

        $index = $this->values->search(function ($item) use ($key) {
            return ($item->key == $key) && ($item->user_id == $this->userId) && ($item->locale == $this->locale);
        });

        if ($index !== false) {
            $this->values->forget($index);
        }

        $this->resetScopes();
    }

    public function forUser(int $id): self
    {
        $this->userId = $id;

        return $this;
    }

    public function forLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function clearCache(): void
    {
        if (config('settings.cache_time') === 'current_request') {
            unset($GLOBALS['justijndepover_settings']);
        } else {
            Cache::forget('justijndepover_settings');
        }
    }

    private function fetchSettings()
    {
        if (config('settings.cache_time') === 'forever') {
            $this->values = Cache::rememberForever('justijndepover_settings', function () {
                return DB::table('settings')->get();
            });
        } else if (config('settings.cache_time') === 'current_request') {
            if (isset($GLOBALS['justijndepover_settings']) && $GLOBALS['justijndepover_settings'] instanceof Collection) {
                $this->values = $GLOBALS['justijndepover_settings'];
            } else {
                $settings = DB::table('settings')->get();
                $GLOBALS['justijndepover_settings'] = $settings;
                $this->values = $settings;
            }
        } else {
            $this->values = Cache::remember('justijndepover_settings', (int) config('settings.cache_time'), function () {
                return DB::table('settings')->get();
            });
        }
    }

    private function resetScopes()
    {
        $this->userId = null;
        $this->locale = null;

        if (config('settings.auto_store_locale') == true) {
            $this->locale = app()->getLocale();
        }
    }
}
