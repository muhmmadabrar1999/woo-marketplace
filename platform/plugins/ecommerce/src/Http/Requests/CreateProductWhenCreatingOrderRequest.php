<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Support\Http\Requests\Request;

class CreateProductWhenCreatingOrderRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'price' => 'numeric|nullable',
        ];
    }
}
