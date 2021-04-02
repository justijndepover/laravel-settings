<?php

namespace Justijndepover\Settings\Concerns;

trait HasSettings
{
    public function settings()
    {
        return settings()->forUser($this->id);
    }
}
