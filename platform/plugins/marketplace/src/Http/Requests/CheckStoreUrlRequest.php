<?php

namespace Woo\Marketplace\Http\Requests;

use Woo\Support\Http\Requests\Request;

class CheckStoreUrlRequest extends Request
{
    public function rules(): array
    {
        return [
            'url' => 'required|max:200',
        ];
    }
}
