<?php

namespace Woo\Ecommerce\Http\Requests;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ProductAttributeSetsRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
            'description' => 'max:400|nullable|string',
            'order' => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
