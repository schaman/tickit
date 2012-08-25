<?php

namespace Tickit\CacheBundle\Engine;

use Tickit\CacheBundle\Exception\ApcCacheUnavailableException;

/**
 * Caching engine for storing data in PHP's APC
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ApcEngine extends AbstractEngine
{

    /**
     * Class constructor, checks whether APC is available
     *
     * @throws ApcCacheUnavailableException If the APC cache is unavailable
     */
    public function __construct()
    {
        if (false === $this->_isAvailable()) {
            throw new ApcCacheUnavailableException();
        }
    }

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


    /**
     * Returns true if APC is available, false otherwise
     *
     * @return bool
     */
    private function _isAvailable()
    {
        return extension_loaded('apc');
    }

}