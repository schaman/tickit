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
        //write data to apc cache
    }

    /**
     * {@inheritDoc}
     */
    protected function internalRead($id)
    {
        return '';
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
     * Returns true if APC is available, false otherwise
     *
     * @return bool
     */
    private function _isAvailable()
    {
        return extension_loaded('apc') && (ini_get('apc.enabled') == '1');
    }

}