<?php

namespace Woo\Ecommerce\Http\Requests;

use BaseHelper;
use Woo\Support\Http\Requests\Request;

class EditAccountRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'phone' => 'sometimes|' . BaseHelper::getPhoneValidationRule(),
            'dob' => 'max:20|sometimes',
        ];
    }
}
