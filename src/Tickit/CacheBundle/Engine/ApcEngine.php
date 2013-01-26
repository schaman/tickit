<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Exception\ApcCacheUnavailableException;
use Tickit\CacheBundle\Options\ApcOptions;

/**
 * Caching engine for storing data in PHP's APC
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ApcEngine extends AbstractEngine
{

    /**
     * Class constructor, checks whether APC is available and sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     * @param \Tickit\CacheBundle\Options\ApcOptions|array              $options   [Optional] An array of options, or an options object, for the cache
     *
     * @throws \Tickit\CacheBundle\Exception\ApcCacheUnavailableException
     */
    public function __construct(ContainerInterface $container, $options = null)
    {
        parent::__construct($container, $options);

        if (false === $this->_isAvailable()) {
            $this->logger->critical('The APC PHP extension has not been enabled / installed', array('engine' => get_class($this)));
            throw new ApcCacheUnavailableException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function internalWrite($id, $data)
    {
        $id = $this->sanitizeIdentifier($id);

        $key = $this->_buildKeyPrefix() . $id;

        $stored = apc_store($key, $data);

        if (false === $stored) {
            $message = sprintf('Permission denied storing data (with identifier of %s) in class %s on line %d', $id, __CLASS__, __LINE__);
            $this->logger->error($message, array('engine' => __CLASS__));
            throw new Exception\PermissionDeniedException($message);
        }

        $this->logger->info(sprintf('Cache WRITE for key value "%s"', $id), array('engine' => get_class($this)));

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        $id = $this->sanitizeIdentifier($id);

        $key = $this->_buildKeyPrefix() . $id;

        if (!$this->_apcExists($key, true)) {
            return null;
        }

        $fetched = true;
        $data = apc_fetch($key, $fetched);

        if (false === $fetched) {
            $this->logger->info(sprintf('Cache MISS for key value "%s"', $id), array('engine' => __CLASS__));
            return null;
        }

        $this->logger->info(sprintf('Cache HIT for key value "%s"', $id), array('engine' => __CLASS__));

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function internalDelete($id)
    {
        $key = $this->_buildKeyPrefix() . $id;

        if (!$this->_apcExists($key)) {
            return false;
        }

        $deleted = apc_delete($key);

        if (true === $deleted) {
            $this->logger->info(sprintf('Cache DELETE for key value "%s"', $id), array('engine' => __CLASS__));
        } else {
            $this->logger->info(sprintf('Cache MISS on DELETE for key value "%s"', $id), array('engine' => __CLASS__));
        }

        return $deleted;
    }

    /**
     * {@inheritDoc}
     */
    protected function setOptions($options)
    {
        if (!$options instanceof ApcOptions) {
            $options = new ApcOptions($options, $this->container);
        }

        return parent::setOptions($options);
    }


    /**
     * Helper function for APC < 3.1.4 in which apc_exists does not exist
     *
     * @param string $key            Key of data in cache
     * @param bool   $progressAnyway Return true regardless of whether apc_exists exists
     *
     * @return mixed
     */
    private function _apcExists($key, $progressAnyway = false)
    {
        if (function_exists('apc_exists')) {
            return apc_exists($key);
        }

        // used when the next logical function will be an apc_fetch anyway
        // no need to run it twice
        if ($progressAnyway) {
            return true;
        }

        $loaded = false;
        apc_fetch($key, $loaded);

        return $loaded;
    }

    /**
     * Builds a prefix based off the current namespace for an APC cache entry
     *
     * @return string
     */
    private function _buildKeyPrefix()
    {
        return $this->getOptions()->getNamespace() . '.';
    }

    /**
     * Returns true if APC is available, false otherwise
     *
     * @return bool
     */
    private function _isAvailable()
    {
        $apcLoaded = extension_loaded('apc');

        if (!$apcLoaded) {
            return false;
        }

        if (php_sapi_name() == 'cli') {
            return (ini_get('apc.enable_cli') == '1');
        }

        return (ini_get('apc.enabled') == '1');
    }

}
