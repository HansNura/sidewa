<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type'];

    // ─── Default Config Values ─────────────────────────────────

    public const DEFAULTS = [
        // Tab: Umum
        'app_name'        => ['group' => 'umum',        'value' => 'Sistem Informasi Desa Terintegrasi', 'type' => 'string'],
        'timezone'        => ['group' => 'umum',        'value' => 'Asia/Jakarta',                      'type' => 'string'],
        'date_format'     => ['group' => 'umum',        'value' => 'DD MMMM YYYY',                     'type' => 'string'],
        'language'        => ['group' => 'umum',        'value' => 'id',                                'type' => 'string'],

        // Tab: Keamanan
        'password_policy'  => ['group' => 'keamanan',   'value' => 'strong',  'type' => 'string'],
        'session_timeout'  => ['group' => 'keamanan',   'value' => '60',      'type' => 'integer'],
        'two_factor'       => ['group' => 'keamanan',   'value' => '0',       'type' => 'boolean'],

        // Tab: Notifikasi
        'notif_internal'   => ['group' => 'notifikasi', 'value' => '1',       'type' => 'boolean'],
        'notif_email'      => ['group' => 'notifikasi', 'value' => '1',       'type' => 'boolean'],
        'notif_whatsapp'   => ['group' => 'notifikasi', 'value' => '1',       'type' => 'boolean'],
        'wa_provider'      => ['group' => 'notifikasi', 'value' => 'fonnte',  'type' => 'string'],

        // Tab: Integrasi
        'dukcapil_url'     => ['group' => 'integrasi',  'value' => 'https://api.dukcapil.kemendagri.go.id/v1/', 'type' => 'string'],
        'dukcapil_api_key' => ['group' => 'integrasi',  'value' => '',        'type' => 'string'],
    ];

    // ─── Static Helpers ────────────────────────────────────────

    /**
     * Get a config value by key, with fallback to defaults.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $config = static::where('key', $key)->first();

        if ($config) {
            return static::castValue($config->value, $config->type);
        }

        // Fallback to defaults
        if (isset(static::DEFAULTS[$key])) {
            return static::castValue(static::DEFAULTS[$key]['value'], static::DEFAULTS[$key]['type']);
        }

        return $default;
    }

    /**
     * Set a config value by key.
     */
    public static function set(string $key, mixed $value): static
    {
        $meta = static::DEFAULTS[$key] ?? ['group' => 'umum', 'type' => 'string'];

        return static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $meta['group'],
                'value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
                'type'  => $meta['type'],
            ]
        );
    }

    /**
     * Get all configs as a flat key => value array.
     */
    public static function allKeyed(): array
    {
        $stored = static::pluck('value', 'key')->toArray();

        $result = [];
        foreach (static::DEFAULTS as $key => $meta) {
            $raw  = $stored[$key] ?? $meta['value'];
            $result[$key] = static::castValue($raw, $meta['type']);
        }

        return $result;
    }

    /**
     * Get configs grouped by tab.
     */
    public static function allGrouped(): array
    {
        $all = static::allKeyed();
        $grouped = [];

        foreach (static::DEFAULTS as $key => $meta) {
            $grouped[$meta['group']][$key] = $all[$key];
        }

        return $grouped;
    }

    /**
     * Cast a stored string value to its proper PHP type.
     */
    private static function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => (bool) $value,
            'json'    => json_decode($value, true),
            default   => $value,
        };
    }
}
