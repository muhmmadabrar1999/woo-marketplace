<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Support\Http\Requests\Request;

class UpdatePrimaryStoreRequest extends Request
{
    public function rules(): array
    {
        return [
            'primary_store_id' => 'required|numeric',
        ];
    }
}
