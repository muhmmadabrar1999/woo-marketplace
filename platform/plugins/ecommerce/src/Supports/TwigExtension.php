<?php

namespace Woo\Ecommerce\Supports;

use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension implements ExtensionInterface
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('price_format', 'format_price'),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_ecommerce_setting', 'get_ecommerce_setting'),
        ];
    }
}
