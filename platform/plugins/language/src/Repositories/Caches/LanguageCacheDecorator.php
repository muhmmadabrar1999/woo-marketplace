<?php

namespace Woo\Language\Repositories\Caches;

use Woo\Support\Repositories\Caches\CacheAbstractDecorator;
use Woo\Language\Repositories\Interfaces\LanguageInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LanguageCacheDecorator extends CacheAbstractDecorator implements LanguageInterface
{
    public function getActiveLanguage(array $select = ['*']): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getDefaultLanguage(array $select = ['*']): ?Model
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
