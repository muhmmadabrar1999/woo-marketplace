<?php

namespace Woo\Ecommerce\Supports;

use Woo\Base\Enums\BaseStatusEnum;
use Woo\Ecommerce\Repositories\Eloquent\ProductAttributeSetRepository;
use Woo\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;

class RenderProductAttributeSetsOnSearchPageSupport
{
    protected ProductAttributeSetInterface|ProductAttributeSetRepository $productAttributeSetRepository;

    public function __construct(ProductAttributeSetInterface $productAttributeSetRepository)
    {
        $this->productAttributeSetRepository = $productAttributeSetRepository;
    }

    public function render(array $params = []): string
    {
        $params = array_merge(['view' => 'plugins/ecommerce::themes.attributes.attributes-filter-renderer'], $params);

        $with = ['attributes'];

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            $with[] = 'attributes.translations';
        }

        $attributeSets = $this->productAttributeSetRepository
            ->advancedGet([
                'condition' => [
                    'status' => BaseStatusEnum::PUBLISHED,
                    'is_searchable' => 1,
                ],
                'order_by' => [
                    'order' => 'ASC',
                ],
                'with' => $with,
            ]);

        return view($params['view'], compact('attributeSets'))->render();
    }
}
