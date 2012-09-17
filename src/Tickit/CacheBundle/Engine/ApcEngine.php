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
    /* @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

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
        $this->container = $container;

        if (false === $this->_isAvailable()) {
            throw new ApcCacheUnavailableException();
        }

        $this->setOptions($options);
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
            throw new Exception\PermissionDeniedException(
                sprintf('Permission denied storing data (with identifier of %s) in class %s on line %d', $id, __CLASS__, __LINE__)
            );
        }

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        $id = $this->sanitizeIdentifier($id);

        $key = $this->_buildKeyPrefix() . $id;

        if (!apc_exists($key)) {
            return null;
        }

        $fetched = true;
        $data = apc_fetch($key, $fetched);

        if (false === $fetched) {
            return null;
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function internalDelete($id)
    {
        $key = $this->_buildKeyPrefix() . $id;

        if (!apc_exists($key)) {
            return false;
        }

        $deleted = apc_delete($key);

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