<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Support\Http\Requests\Request;

class CartRequest extends Request
{
    public function rules(): array
    {
        return [
            'id' => 'required|min:1|integer',
            'qty' => 'min:1|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => __('Product ID is required'),
            'id.integer' => __('Product ID must be a number'),
        ];
    }
}
