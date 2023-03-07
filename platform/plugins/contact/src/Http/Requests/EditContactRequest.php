<?php

namespace Woo\Contact\Http\Requests;

use Woo\Contact\Enums\ContactStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EditContactRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(ContactStatusEnum::values()),
        ];
    }
}
