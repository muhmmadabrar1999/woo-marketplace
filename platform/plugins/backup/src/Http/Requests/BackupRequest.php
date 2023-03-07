<?php

namespace Woo\Backup\Http\Requests;

use Woo\Support\Http\Requests\Request;

class BackupRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}
