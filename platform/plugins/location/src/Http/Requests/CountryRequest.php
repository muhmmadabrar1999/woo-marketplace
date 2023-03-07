<?php

namespace Woo\Location\Http\Requests;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CountryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'nationality' => 'required',
            'order' => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
