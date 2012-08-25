<?php

namespace Tickit\CacheBundle\Engine;

/**
 * Caching engine for the file cache
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FileEngine extends AbstractEngine
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