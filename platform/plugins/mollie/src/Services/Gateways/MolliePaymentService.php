<?php

namespace Woo\Mollie\Services\Gateways;

use Woo\Mollie\Services\Abstracts\MolliePaymentAbstract;
use Exception;
use Illuminate\Http\Request;

class MolliePaymentService extends MolliePaymentAbstract
{
    /**
     * Make a payment
     *
     * @param Request $request
     *
     * @return mixed
     * @throws Exception
     */
    public function makePayment(Request $request)
    {
    }

    /**
     * Use this function to perform more logic after user has made a payment
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function afterMakePayment(Request $request)
    {
    }
}
