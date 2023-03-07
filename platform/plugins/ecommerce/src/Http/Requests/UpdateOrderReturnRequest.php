<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Ecommerce\Enums\OrderReturnStatusEnum;
use Woo\Support\Http\Requests\Request;

class UpdateOrderReturnRequest extends Request
{
    public function rules(): array
    {
        return [
            'return_status' => 'required|string|in:' . implode(',', OrderReturnStatusEnum::values()),
        ];
    }
}
