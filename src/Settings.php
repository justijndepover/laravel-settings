<?php

namespace Justijndepover\Settings;

use Illuminate\Support\Collection;

interface Settings
{
    public function all(): Collection;

    public function get(string $key, string $default = null): ?string;

    public function set($key, string $value = null): void;

    public function has(string $key): bool;

    public function flush(): void;

    public function delete(string $key = null): void;

    public function forget(string $key): void;

    public function forUser(int $id): self;

    public function forLocale(string $locale): self;

    public function clearCache(): void;
}