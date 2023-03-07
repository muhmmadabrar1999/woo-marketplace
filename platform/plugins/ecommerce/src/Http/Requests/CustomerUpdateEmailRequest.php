<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Support\Http\Requests\Request;

class CustomerUpdateEmailRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => 'required|max:60|min:6|email|unique:ec_customers,email,' . $this->route('id'),
        ];
    }
}
