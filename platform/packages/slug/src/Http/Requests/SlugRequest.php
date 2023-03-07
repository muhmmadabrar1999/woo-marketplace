<?php

namespace Woo\Slug\Http\Requests;

use Woo\Support\Http\Requests\Request;

class SlugRequest extends Request
{
    public function rules(): array
    {
        return [
            'value' => 'required',
            'slug_id' => 'required',
        ];
    }
}
