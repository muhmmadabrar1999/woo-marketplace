<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Ecommerce\Enums\GlobalOptionEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductOptionRequest extends Request
{
    public function rules(): array
    {
        return [
            'option_name' => 'required',
            'option_type' => [
                Rule::requiredIf(function () {
                    return $this->input('option_type') == GlobalOptionEnum::NA;
                }),
            ],
        ];
    }
}
