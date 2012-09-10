<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Exception\ApcCacheUnavailableException;

/**
 * Caching engine for storing data in PHP's APC
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ApcEngine extends AbstractEngine
{
    /* @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /**
     * Class constructor, checks whether APC is available and sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     * @param array                                                     $options   [Optional] An array of options for the cache
     *
     * @throws \Tickit\CacheBundle\Exception\ApcCacheUnavailableException
     */
    public function __construct(ContainerInterface $container, array $options = null)
    {
        if (false === $this->_isAvailable()) {
            throw new ApcCacheUnavailableException();
        }

        $this->container = $container;
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
        return extension_loaded('apc') && (ini_get('apc.enabled') == '1');
    }

}