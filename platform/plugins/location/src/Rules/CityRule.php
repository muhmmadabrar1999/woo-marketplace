<?php

namespace Woo\Location\Rules;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Location\Repositories\Interfaces\CityInterface;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class CityRule implements DataAwareRule, Rule
{
    protected array $data = [];

    protected ?string $stateKey;

    public function __construct(?string $stateKey = '')
    {
        $this->stateKey = $stateKey;
    }

    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        $condition = [
            'id' => $value,
            'status' => BaseStatusEnum::PUBLISHED,
        ];

        if ($this->stateKey) {
            $stateId = Arr::get($this->data, $this->stateKey);
            if (! $stateId) {
                return false;
            }
            $condition['state_id'] = $stateId;
        }

        return app(CityInterface::class)->count($condition);
    }

    public function message(): string
    {
        return trans('validation.exists');
    }
}
