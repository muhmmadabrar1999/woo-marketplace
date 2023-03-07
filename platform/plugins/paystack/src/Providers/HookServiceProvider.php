<?php

namespace Woo\Paystack\Providers;

use Woo\Ecommerce\Repositories\Interfaces\OrderAddressInterface;
use Woo\Payment\Enums\PaymentMethodEnum;
use Woo\Paystack\Services\Gateways\PaystackPaymentService;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Paystack;
use Throwable;
use Illuminate\Support\Arr;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, [$this, 'registerPaystackMethod'], 16, 2);
        $this->app->booted(function () {
            add_filter(PAYMENT_FILTER_AFTER_POST_CHECKOUT, [$this, 'checkoutWithPaystack'], 16, 2);
        });

        add_filter(PAYMENT_METHODS_SETTINGS_PAGE, [$this, 'addPaymentSettings'], 97, 1);

        add_filter(BASE_FILTER_ENUM_ARRAY, function ($values, $class) {
            if ($class == PaymentMethodEnum::class) {
                $values['PAYSTACK'] = PAYSTACK_PAYMENT_METHOD_NAME;
            }

            return $values;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_LABEL, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == PAYSTACK_PAYMENT_METHOD_NAME) {
                $value = 'Paystack';
            }

            return $value;
        }, 21, 2);

        add_filter(BASE_FILTER_ENUM_HTML, function ($value, $class) {
            if ($class == PaymentMethodEnum::class && $value == PAYSTACK_PAYMENT_METHOD_NAME) {
                $value = Html::tag(
                    'span',
                    PaymentMethodEnum::getLabel($value),
                    ['class' => 'label-success status-label']
                )
                    ->toHtml();
            }

            return $value;
        }, 21, 2);

        add_filter(PAYMENT_FILTER_GET_SERVICE_CLASS, function ($data, $value) {
            if ($value == PAYSTACK_PAYMENT_METHOD_NAME) {
                $data = PaystackPaymentService::class;
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_PAYMENT_INFO_DETAIL, function ($data, $payment) {
            if ($payment->payment_channel == PAYSTACK_PAYMENT_METHOD_NAME) {
                $paymentService = (new PaystackPaymentService());
                $paymentDetail = $paymentService->getPaymentDetails($payment);
                if ($paymentDetail) {
                    $data = view('plugins/paystack::detail', ['payment' => $paymentDetail, 'paymentModel' => $payment])->render();
                }
            }

            return $data;
        }, 20, 2);

        add_filter(PAYMENT_FILTER_GET_REFUND_DETAIL, function ($data, $payment, $refundId) {
            if ($payment->payment_channel == PAYSTACK_PAYMENT_METHOD_NAME) {
                $refundDetail = (new PaystackPaymentService())->getRefundDetails($refundId);
                if (! Arr::get($refundDetail, 'error')) {
                    $refunds = Arr::get($payment->metadata, 'refunds');
                    $refund = collect($refunds)->firstWhere('data.id', $refundId);
                    $refund = array_merge($refund, Arr::get($refundDetail, 'data', []));

                    return array_merge($refundDetail, [
                        'view' => view('plugins/paystack::refund-detail', ['refund' => $refund, 'paymentModel' => $payment])->render(),
                    ]);
                }

                return $refundDetail;
            }

            return $data;
        }, 20, 3);
    }

    public function addPaymentSettings(?string $settings): string
    {
        return $settings . view('plugins/paystack::settings')->render();
    }

    public function registerPaystackMethod(?string $html, array $data): string
    {
        return $html . view('plugins/paystack::methods', $data)->render();
    }

    public function checkoutWithPaystack(array $data, Request $request)
    {
        if ($request->input('payment_method') == PAYSTACK_PAYMENT_METHOD_NAME) {
            $supportedCurrencies = (new PaystackPaymentService())->supportedCurrencyCodes();

            if (! in_array($data['currency'], $supportedCurrencies)) {
                $data['error'] = true;
                $data['message'] = __(":name doesn't support :currency. List of currencies supported by :name: :currencies.", [
                    'name' => 'Paystack',
                    'currency' => $data['currency'],
                    'currencies' => implode(', ', $supportedCurrencies),
                ]);

                return $data;
            }

            $orderIds = (array) $request->input('order_id', []);
            $orderId = Arr::first($orderIds);
            $orderAddress = $this->app->make(OrderAddressInterface::class)->getFirstBy(['order_id' => $orderId]);

            try {
                $response = Paystack::getAuthorizationResponse([
                    'reference' => Paystack::genTranxRef(),
                    'quantity' => 1,
                    'currency' => $data['currency'],
                    'amount' => (int)$data['amount'] * 100,
                    'email' => $orderAddress ? $orderAddress->email : 'no-email@domain.com',
                    'callback_url' => route('paystack.payment.callback'),
                    'metadata' => json_encode([
                        'order_id' => $orderIds,
                        'customer_id' => $request->input('customer_id'),
                        'customer_type' => $request->input('customer_type'),
                    ]),
                ]);

                if ($response['status']) {
                    header('Location: ' . $response['data']['authorization_url']);
                    exit;
                }

                $data['error'] = true;
                $data['message'] = __('Payment failed!');
            } catch (Throwable $exception) {
                $data['error'] = true;
                $data['message'] = json_encode($exception->getMessage());
            }
        }

        return $data;
    }
}
