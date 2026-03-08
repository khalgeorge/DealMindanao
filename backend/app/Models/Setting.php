<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Cache TTL in seconds (1 hour) */
    const CACHE_TTL = 3600;

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::allCached();
        return $all[$key] ?? $default;
    }

    /**
     * Set (upsert) a setting value and bust the cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        Cache::forget('settings_all');
    }

    /**
     * Get multiple settings as a keyed array (cached).
     *
     * @param  array<string>  $keys
     * @return array<string, mixed>
     */
    public static function getMany(array $keys): array
    {
        $all    = static::allCached();
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $all[$key] ?? null;
        }
        return $result;
    }

    /**
     * Load ALL settings from cache (or DB, then cache).
     *
     * @return array<string, mixed>
     */
    public static function allCached(): array
    {
        return Cache::remember('settings_all', static::CACHE_TTL, function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }
}
