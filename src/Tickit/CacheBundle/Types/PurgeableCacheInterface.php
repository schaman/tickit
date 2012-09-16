<?php

namespace Tickit\CacheBundle\Types;

/**
 * Interface for cache engines that provide purge methods
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface PurgeableCacheInterface
{

    /**
     * Purges the entire cache contents
     *
     * @return void
     */
    public function purgeAll();

    /**
     * Purges the cache contents for a given namespace, if that namespace exists
     *
     * @param string $namespace The namespace to purge
     *
     * @return void
     */
    public function purgeNamespace($namespace);

}