<?php

namespace Woo\Page\Http\Requests;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Page\Supports\Template;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:120',
            'description' => 'max:400',
            'template' => Rule::in(array_keys(Template::getPageTemplates())),
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
