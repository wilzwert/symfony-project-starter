<?php

namespace App\Service;

use Psr\Cache\InvalidArgumentException;

/**
 * A dummy cache manager
 *
 * @author Wilhelm Zwertvaegher
 */
interface CacheManager
{
    /**
     * Simple cache retrieval with a callback that actually loads the data if not cached
     *
     * @param string $key the cache key
     * @param callable $callback to load the actual data if cache is not available
     * @return array the actual data
     * @throws InvalidArgumentException
     */
    public function get(string $key, callable $callback): array;

    /**
     * Clears the cache
     *
     * @return void
     */
    public function clear(): void;
}
