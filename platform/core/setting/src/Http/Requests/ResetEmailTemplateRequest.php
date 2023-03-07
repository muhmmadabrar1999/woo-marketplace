<?php

namespace Woo\Setting\Http\Requests;

use Woo\Support\Http\Requests\Request;

class ResetEmailTemplateRequest extends Request
{
    public function rules(): array
    {
        return [
            'module' => 'required|string|alpha_dash',
            'template_file' => 'required|string|alpha_dash',
        ];
    }
}
