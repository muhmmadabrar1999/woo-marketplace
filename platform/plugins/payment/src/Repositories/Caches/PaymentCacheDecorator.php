<?php

namespace Woo\Payment\Repositories\Caches;

use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Woo\Payment\Repositories\Interfaces\PaymentInterface;

class PaymentCacheDecorator extends CacheAbstractDecorator implements PaymentInterface
{
}
