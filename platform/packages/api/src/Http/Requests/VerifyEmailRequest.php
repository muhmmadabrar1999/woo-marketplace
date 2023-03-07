<?php

namespace Woo\Api\Http\Requests;

use Woo\Support\Http\Requests\Request;

class VerifyEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|string',
            'token' => 'required',
        ];
    }
}
