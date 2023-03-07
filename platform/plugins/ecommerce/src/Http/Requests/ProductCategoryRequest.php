<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Support\Http\Requests\Request;

class ProductCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'order' => 'required|integer|min:0',
        ];
    }
}
