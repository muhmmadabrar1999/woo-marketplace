<?php

namespace Woo\Marketplace\Http\Requests;

use Woo\Marketplace\Enums\RevenueTypeEnum;
use Woo\Marketplace\Repositories\Interfaces\StoreInterface;
use Woo\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRevenueRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'type' => Rule::in(RevenueTypeEnum::adjustValues()),
            'amount' => 'required|numeric|min:0|not_in:0',
            'description' => 'nullable|max:400',
        ];

        if ($this->input('type') == RevenueTypeEnum::SUBTRACT_AMOUNT) {
            $store = app(StoreInterface::class)->findById($this->route('id'));
            if ($store && $store->customer) {
                $customer = $store->customer;
                $rules['amount'] = 'numeric|min:0|max:' . $customer->balance;
            }
        }

        return $rules;
    }
}
