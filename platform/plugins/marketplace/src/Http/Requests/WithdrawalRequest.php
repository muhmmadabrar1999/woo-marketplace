<?php

namespace Woo\Marketplace\Http\Requests;

use Woo\Marketplace\Enums\WithdrawalStatusEnum;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WithdrawalRequest extends Request
{
    public function rules(): array
    {
        return [
            'images' => 'nullable|array',
            'status' => Rule::in(WithdrawalStatusEnum::values()),
            'description' => 'nullable|max:400',
        ];
    }
}
