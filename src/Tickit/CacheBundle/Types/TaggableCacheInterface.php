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
     * Adds a bunch of tags to a cache item
     *
     * @param mixed $id   The identifier of the cache item
     * @param array $tags An array of tags to associate with the cached data
     *
     * @abstract
     *
     * @return void
     */
    public function addTags($id, array $tags);

    /**
     * Reads data from the cache that matches an array of tags
     *
     * @param array $tags         An array of tags used to search the cache
     * @param bool  $partialMatch If true, only one of the tags needs to match for the data to be returned
     *
     * @abstract
     *
     * @return mixed
     */
    public function findByTags(array $tags, $partialMatch = false);


    /**
     * Removes cached data that matches a collection of tags
     *
     * @param array $tags         An array of tags to match on
     * @param bool  $partialMatch If true, only one of the tags needs to match for the data to be removed
     *
     * @abstract
     *
     * @return mixed
     */
    public function removeByTags(array $tags, $partialMatch = false);

}