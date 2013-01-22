<?php

namespace Tickit\CacheBundle\Cache;

use Tickit\CacheBundle\Engine\AbstractEngine;
use Tickit\CacheBundle\Types\PurgeableCacheInterface;
use Tickit\CacheBundle\Types\TaggableCacheInterface;
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
     * @return mixed
     *
     * @throws \Tickit\CacheBundle\Engine\Exception\FeatureNotSupportedException
     */
    public function purge($namespace = null)
    {
        $engine = $this->getEngine();

        if ($engine instanceof PurgeableCacheInterface) {
            if (null !== $namespace) {
                return $engine->purgeNamespace($namespace);
            } else {
                return $engine->purgeAll();
            }
        } else {
            throw new FeatureNotSupportedException(
                sprintf('The requested operation (purge) is not supported in the %s engine', get_class($engine))
            );
        }
    }

    /**
     * Adds tags to a specific cache entry
     *
     * @param mixed $id   The cache key of the data to add tags to
     * @param mixed $tags Either an array of tags or a single tag to add
     *
     * @return mixed
     *
     * @throws \Tickit\CacheBundle\Engine\Exception\FeatureNotSupportedException
     */
    public function addTags($id, $tags)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $engine = $this->getEngine();

        if ($engine instanceof TaggableCacheInterface) {
            return $this->getEngine()->addTags($id, $tags);
        }

        throw new FeatureNotSupportedException(
            sprintf('The requested operation (addTags) is not supported in the %s engine', get_class($engine))
        );
    }

    /**
     * Reads data from the cache that matches a set of tags
     *
     * @param mixed $tags         Either an array of tags or a single tag to search on
     * @param bool  $partialMatch [Optional] True to only match on part of the tag name, defaults to false
     *
     * @return array
     *
     * @throws \Tickit\CacheBundle\Engine\Exception\FeatureNotSupportedException
     */
    public function findByTags($tags, $partialMatch = false)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $engine = $this->getEngine();

        if ($engine instanceof TaggableCacheInterface) {
            return $this->getEngine()->findByTags($tags, $partialMatch);
        }

        throw new FeatureNotSupportedException(
            sprintf('The requested operation (findByTags) is not supported in the %s engine', get_class($engine))
        );
    }

    /**
     * Deletes data from the cache based on its tags
     *
     * @param mixed $tags         Either an array of tags or a single tag to search on
     * @param bool  $partialMatch [Optional] True to only match on part of the tag name, defaults to false
     *
     * @return bool
     *
     * @throws \Tickit\CacheBundle\Engine\Exception\FeatureNotSupportedException
     */
    public function removeByTags($tags, $partialMatch = false)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $engine = $this->getEngine();

        if ($engine instanceof TaggableCacheInterface) {
            return $this->getEngine()->removeByTags($tags, $partialMatch);
        }

        throw new FeatureNotSupportedException(
            sprintf('The requested operation (removeByTags) is not supported in the %s engine', get_class($engine))
        );
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