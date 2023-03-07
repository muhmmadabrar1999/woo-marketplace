<?php

namespace Woo\Payment\Services\Gateways;

use Woo\Payment\Enums\PaymentMethodEnum;
use Woo\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Str;

class CodPaymentService
{
    public function execute(array $data): string
    {
        $chargeId = Str::upper(Str::random(10));

        $orderIds = $data['order_id'];

        do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'charge_id' => $chargeId,
            'order_id' => $orderIds,
            'customer_id' => $data['customer_id'],
            'customer_type' => $data['customer_type'],
            'payment_channel' => PaymentMethodEnum::COD,
            'status' => PaymentStatusEnum::PENDING,
        ]);

        return $chargeId;
    }
}
