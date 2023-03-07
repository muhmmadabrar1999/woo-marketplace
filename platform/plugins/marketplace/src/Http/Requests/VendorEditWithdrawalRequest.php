<?php

namespace Woo\Marketplace\Http\Requests;

use Woo\Support\Http\Requests\Request;

class VendorEditWithdrawalRequest extends Request
{
    public function rules(): array
    {
        return [
            'description' => 'nullable|max:400',
        ];
    }
}
