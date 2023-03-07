<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductLabelRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
