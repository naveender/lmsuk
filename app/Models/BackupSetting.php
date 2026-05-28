<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupSetting extends Model
{
    protected $table = 'aspire_settings';

    protected $fillable = ['key', 'value', 'group'];

    public static function get($key, $default = null)
    {
        return cache()->remember("setting_{$key}", 86400, function () use ($key, $default) {
            return self::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set($key, $value, $group = null)
    {
        cache()->forget("setting_{$key}");

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}
