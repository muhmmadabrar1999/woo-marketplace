<?php

namespace Woo\Translation\Http\Requests;

use Woo\Support\Http\Requests\Request;

class TranslationRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
