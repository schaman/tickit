<?php

namespace Tickit\CacheBundle\Engine;

/**
 * Caching engine for storing data in memcached server instance(s)
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedEngine extends AbstractEngine
{

    /**
     * {@inheritDoc}
     */
    public function write($id, $data, array $tags = null)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function read($id)
    {
        return '';
    }

}