<?php

namespace Woo\Ecommerce\Models;

use Woo\Base\Models\BaseModel;
use Woo\Ecommerce\Repositories\Interfaces\ShippingRuleInterface;
use Woo\Ecommerce\Traits\LocationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipping extends BaseModel
{
    use LocationTrait;

    protected $table = 'ec_shipping';

    protected $fillable = [
        'title',
        'country',
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (Shipping $shipping) {
            app(ShippingRuleInterface::class)->deleteBy(['shipping_id' => $shipping->id]);
        });
    }

    public function rules(): HasMany
    {
        return $this->hasMany(ShippingRule::class, 'shipping_id');
    }
}
