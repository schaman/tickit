<?php

namespace Tickit\CacheBundle\Cache;

use Tickit\CacheBundle\Engine\AbstractEngine;
use Tickit\CacheBundle\Types\PurgeableCacheInterface;
use Tickit\CacheBundle\Engine\Exception\FeatureNotSupportedException;

/**
 * Base cache file that integrates an engine and an options object
 * to provider read/write functionality
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class Cache
{
    /* @var \Tickit\CacheBundle\Engine\AbstractEngine $engine */
    protected $engine;

    /**
     * Class constructor, sets up engine
     *
     * @param \Tickit\CacheBundle\Engine\AbstractEngine $engine The engine adapter
     */
    public function __construct(AbstractEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Reads data from the cache engine
     *
     * @param string|int $id
     *
     * @return mixed
     */
    public function read($id)
    {
        return $this->getEngine()->internalRead($id);
    }

    /**
     * Writes a bunch of data to the cache engine
     *
     * @param string|int $id   The unique ID for this piece of data
     * @param mixed      $data The data to write to the cache
     *
     * @return mixed
     */
    public function write($id, $data)
    {
        return $this->getEngine()->internalWrite($id, $data);
    }

    /**
     * Deletes data from the cache engine
     *
     * @param string|int $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->getEngine()->internalDelete($id);
    }


    /**
     * Purges all data from the cache, if the $namespace parameter is provided then data
     * will be purged from that namespace only (if it exists, otherwise nothing will be purged)
     *
     * @param string $namespace [Optional] A namespace to purge
     *
     * @throws \Tickit\CacheBundle\Engine\Exception\FeatureNotSupportedException
     */
    public function purge($namespace = null)
    {
        $engine = $this->getEngine();

        if ($engine instanceof PurgeableCacheInterface) {
            if (null !== $namespace) {
                $engine->purgeNamespace($namespace);
            } else {
                $engine->purgeAll();
            }
        } else {
            throw new FeatureNotSupportedException(
                sprintf('The requested operation (purge) is not supported in the %s engine', get_class($engine))
            );
        }
    }

    /**
     * Gets the caching engine associated with this cache
     *
     * @return \Tickit\CacheBundle\Engine\AbstractEngine
     */
    public function getEngine()
    {
        return $this->engine;
    }
}