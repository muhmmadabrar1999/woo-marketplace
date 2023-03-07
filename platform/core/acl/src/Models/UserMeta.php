<?php

namespace Woo\ACL\Models;

use Illuminate\Support\Facades\Auth;
use Woo\Base\Models\BaseModel;

class UserMeta extends BaseModel
{
    protected $table = 'user_meta';

    protected $fillable = [
        'key',
        'value',
        'user_id',
    ];

    public static function setMeta(string $key, $value = null, int $userId = 0): bool
    {
        if ($userId == 0) {
            $userId = Auth::id();
        }

        $meta = self::firstOrCreate([
            'user_id' => $userId,
            'key' => $key,
        ]);

        return $meta->update(['value' => $value]);
    }

    public static function getMeta(string $key, $default = null, int $userId = 0): ?string
    {
        if ($userId == 0) {
            $userId = Auth::id();
        }

        $meta = self::where([
            'user_id' => $userId,
            'key' => $key,
        ])->select('value')->first();

        if (! empty($meta)) {
            return $meta->value;
        }

        return $default;
    }
}
