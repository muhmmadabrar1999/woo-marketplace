<?php

namespace Woo\LanguageAdvanced\Http\Requests;

use Woo\Support\Http\Requests\Request;

class LanguageAdvancedRequest extends Request
{
    public function rules(): array
    {
        return [
            'model' => 'required|max:255',
        ];
    }
}
