<?php

namespace Woo\Ecommerce\Option\OptionType;

use Woo\Ecommerce\Option\Interfaces\OptionTypeInterface;

class Dropdown extends BaseOptionType implements OptionTypeInterface
{
    public function view(): string
    {
        return 'dropdown';
    }
}
