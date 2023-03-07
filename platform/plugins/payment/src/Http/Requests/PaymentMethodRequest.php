<?php

namespace Woo\Payment\Http\Requests;

use Woo\Support\Http\Requests\Request;

class PaymentMethodRequest extends Request
{
    public function rules(): array
    {
        return [
            'type' => 'required|max:120',
        ];
    }
}
