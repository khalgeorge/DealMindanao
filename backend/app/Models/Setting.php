<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $record = static::where('key', $key)->first();
        return $record ? $record->value : $default;
    }

    /**
     * Set (upsert) a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get multiple settings as a keyed array.
     *
     * @param  array<string>  $keys
     * @return array<string, mixed>
     */
    public static function getMany(array $keys): array
    {
        $records = static::whereIn('key', $keys)->pluck('value', 'key');
        $result  = [];
        foreach ($keys as $key) {
            $result[$key] = $records[$key] ?? null;
        }
        return $result;
    }
}
