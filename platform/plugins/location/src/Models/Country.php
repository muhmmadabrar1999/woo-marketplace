<?php

namespace Woo\Location\Models;

use Woo\Base\Casts\SafeContent;
use Woo\Base\Enums\BaseStatusEnum;
use Woo\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends BaseModel
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'nationality',
        'code',
        'order',
        'is_default',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'nationality' => SafeContent::class,
        'code' => SafeContent::class,
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Country $country) {
            State::where('country_id', $country->id)->delete();
            City::where('country_id', $country->id)->delete();
        });
    }
}
