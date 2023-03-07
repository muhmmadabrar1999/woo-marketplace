<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Ecommerce\Enums\ShippingStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateShipmentStatusRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => 'required|' . Rule::in(ShippingStatusEnum::values()),
        ];
    }
}
