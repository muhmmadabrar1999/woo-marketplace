{!! Form::open(['url' => $url]) !!}

    <div class="form-group">
        <label for="shipment-status" class="control-label">{{ trans('plugins/ecommerce::shipping.status') }}</label>
        @if (MarketplaceHelper::allowVendorManageShipping())
            {!! Form::customSelect('status', \Woo\Ecommerce\Enums\ShippingStatusEnum::labels(), $shipment->status) !!}
        @else
            {!! Form::customSelect('status', [
                \Woo\Ecommerce\Enums\ShippingStatusEnum::ARRANGE_SHIPMENT => \Woo\Ecommerce\Enums\ShippingStatusEnum::ARRANGE_SHIPMENT()->label(),
                \Woo\Ecommerce\Enums\ShippingStatusEnum::READY_TO_BE_SHIPPED_OUT => \Woo\Ecommerce\Enums\ShippingStatusEnum::READY_TO_BE_SHIPPED_OUT()->label()
            ], $shipment->status) !!}
        @endif
    </div>

{!! Form::close() !!}
