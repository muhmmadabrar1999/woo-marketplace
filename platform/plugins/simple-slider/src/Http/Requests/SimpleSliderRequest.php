<?php

namespace Woo\SimpleSlider\Http\Requests;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SimpleSliderRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'key' => 'required',
            'description' => 'max:1000',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
