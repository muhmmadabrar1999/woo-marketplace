<?php

namespace Woo\Theme\Http\Requests;

use Woo\Support\Http\Requests\Request;

class CustomCssRequest extends Request
{
    public function rules(): array
    {
        return [
            'custom_css' => 'nullable|string',
        ];
    }
}
