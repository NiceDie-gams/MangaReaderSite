<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    private const CACHE_KEY = 'site_settings';

    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return SiteSetting::pluck('value', 'key')->toArray();
        });
    }

    public function get(string $key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        SiteSetting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function isCommentsEnabled(): bool
    {
        return filter_var($this->get('comments_enabled', true), FILTER_VALIDATE_BOOLEAN);
    }

    public function isReportsEnabled(): bool
    {
        return filter_var($this->get('reports_enabled', true), FILTER_VALIDATE_BOOLEAN);
    }

    public function isRegistrationEnabled(): bool
    {
        return filter_var($this->get('registration_enabled', true), FILTER_VALIDATE_BOOLEAN);
    }
}
