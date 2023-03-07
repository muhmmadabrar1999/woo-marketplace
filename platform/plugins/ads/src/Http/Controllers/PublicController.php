<?php

namespace Woo\Ads\Http\Controllers;

use Woo\Ads\Repositories\Interfaces\AdsInterface;
use Woo\Base\Http\Controllers\BaseController;
use Woo\Base\Http\Responses\BaseHttpResponse;

class PublicController extends BaseController
{
    protected AdsInterface $adsRepository;

    public function __construct(AdsInterface $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    public function getAdsClick(string $key, BaseHttpResponse $response)
    {
        $ads = $this->adsRepository->getFirstBy(compact('key'));

        if (! $ads || ! $ads->url) {
            return $response->setNextUrl(route('public.single'));
        }

        $ads->clicked++;
        $ads->save();

        return $response->setNextUrl($ads->url);
    }
}
