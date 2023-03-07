<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Ecommerce\Enums\ShippingCodStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateShipmentCodStatusRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => 'required|' . Rule::in(ShippingCodStatusEnum::values()),
        ];
    }
}
