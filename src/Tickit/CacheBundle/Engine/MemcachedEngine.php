<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Exception\MemcachedCacheUnavailableException;
use Tickit\CacheBundle\Options\MemcachedOptions;
use Memcached;

/**
 * Caching engine for storing data in memcached server instance(s)
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedEngine extends AbstractEngine
{

    /* @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /* @var \Memcached */
    protected $memcached;

    /**
     * Class constructor, sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     * @param array                                                     $options   An array of options for the cache
     *
     * @throws MemcachedCacheUnavailableException
     */
    public function __construct(ContainerInterface $container, array $options = null)
    {
        $this->container = $container;

        if (false === $this->_isAvailable()) {
            throw new MemcachedCacheUnavailableException();
        }

        $this->setOptions($options);

        // TODO: load this from options
        $instanceId = 'tickit';
        $this->memcached = new Memcached($instanceId);

        // TODO: load server from configuration
        $this->memcached->addServer('127.0.0.1', 11211);
    }

    /**
     * {@inheritDoc}
     */
    public function internalWrite($id, $data)
    {
        //write data to memcached cache
        $this->memcached->set($id, $data);

        $resultCode = $this->memcached->getResultCode();
        if ($resultCode !== Memcached::RES_SUCCESS) {
            throw new Exception\PermissionDeniedException(
                sprintf('Permission denied storing data (with identifier of %s) in class %s on line %d. Memcached result code: %d', $id, __CLASS__, __LINE__, $resultCode)
            );
        }

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        $data = $this->memcached->get($id);

        if ($this->memcached->getResultCode() == Memcached::RES_SUCCESS) {
            return $data;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function internalDelete($id)
    {
        $this->memcached->delete($id);

        return ($this->memcached->getResultCode() == Memcached::RES_SUCCESS);
    }


    /**
     * {@inheritDoc}
     */
    protected function setOptions($options)
    {
        if (!$options instanceof MemcachedOptions) {
            $options = new MemcachedOptions($options, $this->container);
        }

        $this->options = $options;

        return $this->options;
    }

    /**
     * Returns true if Memcached is available, false otherwise
     *
     * @return bool
     */
    private function _isAvailable()
    {
        return extension_loaded('memcached');
    }
}
