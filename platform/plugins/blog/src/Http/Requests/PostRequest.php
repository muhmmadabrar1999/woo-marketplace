<?php

namespace Woo\Blog\Http\Requests;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Blog\Supports\PostFormat;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PostRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'max:400',
            'categories' => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];

        $postFormats = PostFormat::getPostFormats(true);

        if (count($postFormats) > 1) {
            $rules['format_type'] = Rule::in(array_keys($postFormats));
        }

        return $rules;
    }
}
