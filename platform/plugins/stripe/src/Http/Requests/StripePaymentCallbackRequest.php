<?php

namespace Woo\Stripe\Http\Requests;

use Woo\Support\Http\Requests\Request;

class StripePaymentCallbackRequest extends Request
{
    public function rules(): array
    {
        return [
            'session_id' => 'required|min:66|max:66',
        ];
    }
}
