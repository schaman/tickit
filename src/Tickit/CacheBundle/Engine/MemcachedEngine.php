<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Exception\MemcachedCacheUnavailableException;
use Tickit\CacheBundle\Options\MemcachedOptions;
use Tickit\CacheBundle\Types\PurgeableCacheInterface;
use Memcached;

/**
 * Caching engine for storing data in memcached server instance(s)
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class MemcachedEngine extends AbstractEngine implements PurgeableCacheInterface
{

    /**
     * The internal memcached PHP object
     *
     * @var \Memcached
     */
    protected $memcached;

    /**
     * MemcachedOptions object
     *
     * @var MemcachedOptions
     */
    protected $options;

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
        if (false === $this->_isAvailable()) {
            $this->logger->critical('The memcached PHP extension has not been enabled / installed', array('engine' => __CLASS__));
            throw new MemcachedCacheUnavailableException();
        }

        parent::__construct($container, $options);

        $this->memcached = $this->options->getMemcached();
    }

    /**
     * {@inheritDoc}
     */
    public function internalWrite($id, $data)
    {
        //write data to memcached cache
        $this->memcached->set($id, $data);

        $resultCode = $this->memcached->getResultCode();
        $resultMessage = $this->memcached->getResultMessage();
        if ($resultCode !== Memcached::RES_SUCCESS) {
            $message = sprintf('Permission denied storing data (with identifier of %s) in class %s on line %d. Memcached result code: %d (%s)', $id, __CLASS__, __LINE__, $resultCode, $resultMessage);
            $this->logger->error($message, array('engine' => __CLASS__));
            throw new Exception\PermissionDeniedException($message);
        }

        $this->logger->info(sprintf('Cache WRITE for key value "%s"', $id), array('engine' => __CLASS__));

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        $data = $this->memcached->get($id);

        if ($this->memcached->getResultCode() == Memcached::RES_SUCCESS) {
            $this->logger->info(sprintf('Cache HIT for key value "%s"', $id), array('engine' => __CLASS__));
            return $data;
        }

        $this->logger->info(sprintf('Cache MISS for key value "%s"', $id), array('engine' => __CLASS__));

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function internalDelete($id)
    {
        $this->memcached->delete($id);

        $success = ($this->memcached->getResultCode() == Memcached::RES_SUCCESS);

        if (false !== $success) {
            $this->logger->info(sprintf('Cache DELETE for key value "%s"', $id), array('engine' => __CLASS__));
        }

        return $success;
    }


    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function purgeAll()
    {
        $success = $this->memcached->flush();

        if (false !== $success) {
            $this->logger->info('Cache PURGE for all data', array('engine' => __CLASS__));
        }

        return $success;
    }

    /**
     * This method is not supported in the Memcached engine. May look to
     * emulate this in a future version
     *
     * @param string $namespace The namespace to purge
     *
     * @return bool
     */
    public function purgeNamespace($namespace)
    {
        return false;
    }


    /**
     * {@inheritDoc}
     *
     * @param mixed $options Either an array of options or a valid options object instance
     *
     * @return \Tickit\CacheBundle\Options\MemcachedOptions
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
