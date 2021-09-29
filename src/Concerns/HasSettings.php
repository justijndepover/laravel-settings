<?php

namespace Justijndepover\Settings\Concerns;

trait HasSettings
{
    public function settings()
    {
        return settings()->forUser($this->id);
    }

    public function getSettingsAttribute()
    {
        return settings()->forUser($this->id);
    }
}
