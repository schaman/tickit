<?php

namespace Tickit\CacheBundle\Types;

/**
 * Interface for caches that support tagging
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
interface TaggableCacheInterface
{
    /**
     * Writes data to the cache and adds tags
     *
     * @param mixed $data An object, array or string of data to cache
     * @param array $tags An array of tags to associate with the cached data
     * @param int   $id   [Optional] A unique identifier for the cached data, if not provided one will automatically be generated
     *
     * @abstract
     *
     * @return void
     */
    public function writeWithTags($data, array $tags, $id = null);

    /**
     * Reads data from the cache that matches an array of tags
     *
     * @param array $tags An array of tags used to search the cache
     *
     * @abstract
     *
     * @return mixed
     */
    public function readFromTags(array $tags);

}