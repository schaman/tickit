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
     * Constructs the options object and resolves
     *
     * @param array                                                     $options   An array of user defined options
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The symfony service container instance
     */
    public function __construct(array $options, ContainerInterface $container)
    {
        $this->container = $container;
        $this->_resolveOptions($options);
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
     * Sanitizes a namespace ready for use
     *
     * @param string $namespace The unsanitized namespace
     *
     * @return mixed
     */
    protected function _sanitizeNamespace($namespace)
    {
        return preg_replace('/^[a-zA-Z0-9]/', '', $namespace);
    }


    /**
     * Resolves options and sets properties on the options object
     *
     * @param array $options The raw array of user defined options
     */
    protected function _resolveOptions(array $options)
    {
        if (isset($options['namespace'])) {
            try {
                $this->setNamespace($options['namespace']);
            } catch (InvalidOptionException $e) {
                //do nothing
            }
        }

        if (null === $this->namespace) {
            $defaultNamespace = $this->container->getParameter('tickit_cache.default_namespace');
            $this->setNamespace($defaultNamespace);
        }
    }

}