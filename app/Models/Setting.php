<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $table = 'app_settings';
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        if (!Schema::hasTable('app_settings')) {
            return $default;
        }
        $row = static::query()->where('key', $key)->first();
        return $row ? $row->value : $default;
    }

    public static function set(string $key, $value): void
    {
        if (!Schema::hasTable('app_settings')) {
            return; // Si aún no existe la tabla, evitamos error. Ejecutar migraciones para persistir.
        }
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}


