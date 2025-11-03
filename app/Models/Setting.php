<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    /**
     * Get setting value by key dengan fallback ke default
     */
    public static function get($key, $default = null)
    {
        // Cek cache dulu untuk performa
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Update cache
        Cache::forever("setting_{$key}", $value);

        return $setting;
    }

    /**
     * Get multiple settings sekaligus
     */
    public static function getMultiple(array $keys)
    {
        $results = [];
        foreach ($keys as $key => $default) {
            $results[$key] = self::get($key, $default);
        }
        return $results;
    }
}