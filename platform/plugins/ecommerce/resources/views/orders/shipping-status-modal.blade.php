{!! Form::open(['url' => $url]) !!}

    <div class="form-group">
        <label for="shipment-status" class="control-label">{{ trans('plugins/ecommerce::shipping.status') }}</label>
        {!! Form::customSelect('status', \Woo\Ecommerce\Enums\ShippingStatusEnum::labels(), $shipment->status) !!}
    </div>

{!! Form::close() !!}
