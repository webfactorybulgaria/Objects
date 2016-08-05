<?php

namespace TypiCMS\Modules\Objects\Repositories;

use TypiCMS\Modules\Core\Custom\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Custom\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements ObjectInterface
{
    public function __construct(ObjectInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }
}
