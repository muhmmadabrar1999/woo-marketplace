<?php

namespace Woo\Marketplace\Http\Requests;

use Woo\Ecommerce\Enums\ShippingStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
use MarketplaceHelper;

class UpdateShippingStatusRequest extends Request
{
    public function rules(): array
    {
        if (MarketplaceHelper::allowVendorManageShipping()) {
            return [
                'status' => Rule::in(ShippingStatusEnum::values()),
            ];
        }

        return [
            'status' => Rule::in([ShippingStatusEnum::ARRANGE_SHIPMENT, ShippingStatusEnum::READY_TO_BE_SHIPPED_OUT]),
        ];
    }
}
