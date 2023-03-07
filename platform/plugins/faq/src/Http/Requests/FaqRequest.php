<?php

namespace Woo\Faq\Http\Requests;

use Woo\Support\Http\Requests\Request;

class FaqRequest extends Request
{
    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ];
    }
}
