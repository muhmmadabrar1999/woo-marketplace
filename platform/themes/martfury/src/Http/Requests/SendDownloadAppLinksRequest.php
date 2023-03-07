<?php

namespace Theme\WooMarketplace\Http\Requests;

use Woo\Support\Http\Requests\Request;

class SendDownloadAppLinksRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
