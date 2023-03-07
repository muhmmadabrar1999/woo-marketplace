<?php

namespace Woo\Setting\Http\Requests;

use Woo\Support\Http\Requests\Request;

class SendTestEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
