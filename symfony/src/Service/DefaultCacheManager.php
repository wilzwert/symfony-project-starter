<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Autoconfigure]
readonly class DefaultCacheManager implements CacheManager
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    #[\Override]
    public function get(string $key, callable $callback): array
    {
        return $this->cache->get(
            md5(strtolower($key)),
            fn (ItemInterface $item) => $callback()
        );
    }

    #[\Override]
    public function clear(): void
    {
        if ($this->cache instanceof AbstractAdapter) {
            $this->cache->clear();
        }
    }
}
