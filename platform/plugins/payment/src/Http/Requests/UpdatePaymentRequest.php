<?php

namespace Woo\Payment\Http\Requests;

use Woo\Payment\Enums\PaymentStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(PaymentStatusEnum::values()),
        ];
    }
}
