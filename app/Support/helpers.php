<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    function setting(string $key, ?string $default = null): ?string
    {
        static $cache = [];

        if (! array_key_exists($key, $cache)) {
            try {
                $cache[$key] = Setting::get($key);
            } catch (Throwable) {
                $cache[$key] = null;
            }
        }

        return $cache[$key] ?? $default;
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('rupiah')) {
    function rupiah(float|int|string|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}

if (! function_exists('setting_localized')) {
    function setting_localized(string $key, ?string $default = null): ?string
    {
        if (app()->getLocale() !== config('app.default_locale')) {
            $value = setting($key.'_en');

            if (filled($value)) {
                return $value;
            }
        }

        return setting($key, $default);
    }
}

if (! function_exists('lroute')) {
    /**
     * Generate a route URL for the active locale. The default locale (id) has no prefix.
     *
     * @param  array<string, mixed>  $parameters
     */
    function lroute(string $name, array $parameters = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale !== config('app.default_locale')) {
            return route('en.'.$name, $parameters);
        }

        return route($name, $parameters);
    }
}

if (! function_exists('locale_url')) {
    function locale_url(string $locale): string
    {
        $route = request()->route();

        if (! $route || ! $route->getName()) {
            return $locale === config('app.default_locale') ? url('/') : url('/'.$locale);
        }

        $name = $route->getName();
        $name = str_starts_with($name, 'en.') ? substr($name, 3) : $name;

        return lroute($name, $route->parameters(), $locale);
    }
}
