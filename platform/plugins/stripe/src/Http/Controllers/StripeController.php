<?php

namespace Woo\Stripe\Http\Controllers;

use Woo\Base\Http\Responses\BaseHttpResponse;
use Woo\Payment\Enums\PaymentStatusEnum;
use Woo\Payment\Supports\PaymentHelper;
use Woo\Stripe\Http\Requests\StripePaymentCallbackRequest;
use Woo\Stripe\Services\Gateways\StripePaymentService;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    public function success(StripePaymentCallbackRequest $request, StripePaymentService $stripePaymentService, BaseHttpResponse $response)
    {
        try {
            $stripePaymentService->setClient();

            $session = Session::retrieve($request->input('session_id'));

            if ($session->payment_status == 'paid') {
                $metadata = $session->metadata->toArray();

                $orderIds = json_decode($metadata['order_id'], true);

                $charge = PaymentIntent::retrieve($session->payment_intent);

                if (! $charge->charges) {
                    return $response
                        ->setError()
                        ->setNextUrl(PaymentHelper::getCancelURL())
                        ->setMessage(__('Payment failed!'));
                }

                $chargeId = $charge->charges->first()->id;

                do_action(PAYMENT_ACTION_PAYMENT_PROCESSED, [
                    'amount' => $metadata['amount'],
                    'currency' => strtoupper($session->currency),
                    'charge_id' => $chargeId,
                    'order_id' => $orderIds,
                    'customer_id' => Arr::get($metadata, 'customer_id'),
                    'customer_type' => Arr::get($metadata, 'customer_type'),
                    'payment_channel' => STRIPE_PAYMENT_METHOD_NAME,
                    'status' => PaymentStatusEnum::COMPLETED,
                ]);

                return $response
                    ->setNextUrl(PaymentHelper::getRedirectURL() . '?charge_id=' . $chargeId)
                    ->setMessage(__('Checkout successfully!'));
            }

            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->setMessage(__('Payment failed!'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(PaymentHelper::getCancelURL())
                ->withInput()
                ->setMessage($exception->getMessage() ?: __('Payment failed!'));
        }
    }

    public function error(BaseHttpResponse $response)
    {
        return $response
            ->setError()
            ->setNextUrl(PaymentHelper::getCancelURL())
            ->withInput()
            ->setMessage(__('Payment failed!'));
    }
}
