<?php

namespace Woo\Ecommerce\Models;

use Woo\Base\Casts\SafeContent;
use Woo\Base\Models\BaseModel;
use Woo\Ecommerce\Enums\ShippingRuleTypeEnum;
use Woo\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingRule extends BaseModel
{
    protected $table = 'ec_shipping_rules';

    protected $fillable = [
        'name',
        'price',
        'type',
        'from',
        'to',
        'shipping_id',
    ];

    protected $casts = [
        'type' => ShippingRuleTypeEnum::class,
        'name' => SafeContent::class,
    ];

    public function shipping(): BelongsTo
    {
        return $this->belongsTo(Shipping::class)->withDefault();
    }

    public function items(): HasMany
    {
        return $this->hasMany(ShippingRuleItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ShippingRule $shippingRule) {
            app(ShippingRuleItemInterface::class)->deleteBy(['shipping_rule_id' => $shippingRule->id]);
        });
    }
}
