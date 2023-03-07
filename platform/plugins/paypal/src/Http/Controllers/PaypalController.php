<?php

namespace Woo\Paypal\Http\Controllers;

use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Paypal\Http\Requests\PayPalPaymentCallbackRequest;
use Woo\Paypal\Services\Gateways\PayPalPaymentService;
use Woo\Payment\Supports\PaymentHelper;
use Illuminate\Routing\Controller;

class PaypalController extends Controller
{
    public function getCallback(
        PayPalPaymentCallbackRequest $request,
        PayPalPaymentService $payPalPaymentService,
        BaseHttpResponse $response
    ) {
        $status = $payPalPaymentService->getPaymentStatus($request);

        if (! $status) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage(__('Payment failed!'));
        }

        $payPalPaymentService->afterMakePayment($request->input());

        return $response
            ->setNextUrl(PaymentHelper::getRedirectURL())
            ->setMessage(__('Checkout successfully!'));
    }
}
