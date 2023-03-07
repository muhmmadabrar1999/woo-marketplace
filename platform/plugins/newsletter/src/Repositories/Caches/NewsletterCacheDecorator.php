<?php

namespace Woo\Newsletter\Repositories\Caches;

use Woo\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Woo\Support\Repositories\Caches\CacheAbstractDecorator;

class NewsletterCacheDecorator extends CacheAbstractDecorator implements NewsletterInterface
{
}
