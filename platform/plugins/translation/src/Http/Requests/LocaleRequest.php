<?php

namespace Woo\Translation\Http\Requests;

use Woo\Base\Supports\Language;
use Woo\Support\Http\Requests\Request;

class LocaleRequest extends Request
{
    public function rules(): array
    {
        return [
            'locale' => 'required|in:' . implode(',', collect(Language::getListLanguages())->pluck(0)->unique()->all()),
        ];
    }
}
