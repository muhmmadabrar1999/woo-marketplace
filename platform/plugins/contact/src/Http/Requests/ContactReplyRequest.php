<?php

namespace Woo\Contact\Http\Requests;

use Woo\Support\Http\Requests\Request;

class ContactReplyRequest extends Request
{
    public function rules(): array
    {
        return [
            'message' => 'required',
        ];
    }
}
