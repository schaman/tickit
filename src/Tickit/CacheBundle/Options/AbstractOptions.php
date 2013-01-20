<?php

namespace Tickit\CacheBundle\Options;

use Tickit\CacheBundle\Options\Exception\InvalidOptionException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract options resolver for all caching engines
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractOptions
{
    /**
     * The namespace of the current cache engine instance
     *
     * @var string $namespace
     */
    protected $namespace;

    /**
     * The symfony service container instance that contains application configuration values
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * The array of options passed to this resolver object
     *
     * @var array $options
     */
    protected $options;

    /**
     * Constructs the options object and resolves
     *
     * @param array                                                     $options   An array of user defined options
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The symfony service container instance
     */
    public function __construct(array $options, ContainerInterface $container)
    {
        $this->options = $options;
        $this->container = $container;
        $this->_resolveOptions();
    }

    /**
     * Sets the current namespace
     *
     * @param string $namespace The new namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $this->_sanitizeNamespace($namespace);
    }

    /**
     * Returns the current namespace for this set of options
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Sanitizes a namespace ready for use
     *
     * @param string $namespace The unsanitized namespace
     *
     * @return mixed
     */
    protected function _sanitizeNamespace($namespace)
    {
        return preg_replace('/[^a-zA-Z0-9\.]*/', '', $namespace);
    }

    /**
     * Resolves options and sets properties on the options object
     */
    protected function _resolveOptions()
    {
        $namespace = $this->getRawOption('namespace', null);

        if (!empty($namespace)) {
            $this->setNamespace($namespace);
        } else {
            $defaultNamespace = $this->container->getParameter('tickit_cache.default_namespace');
            $this->setNamespace($defaultNamespace);
        }
    }

    /**
     * Returns a value determined by its key from the raw array of options passed
     * into the option resolver's constructor
     *
     * @param string $name          The name of the option to fetch
     * @param mixed  $fallbackValue The value to return if the option does not exist
     *
     * @return mixed
     */
    protected function getRawOption($name, $fallbackValue)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }

        return $fallbackValue;
    }

}