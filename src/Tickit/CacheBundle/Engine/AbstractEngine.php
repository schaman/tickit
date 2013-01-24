<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Options\AbstractOptions;
use Tickit\CacheBundle\Util\Sanitizer;

/**
 * Abstract caching engine providing base functionality for data caching
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractEngine
{

    /**
     * An instance of the dependency injection container
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * An instance containing engine options
     *
     * @var \Tickit\CacheBundle\Options\AbstractOptions $options
     */
    protected $options;

    /**
     * An instance of the application logger
     *
     * @var \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * Class constructor, will set the container and fire options resolver
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     * @param array                                                     $options   [Optional] An array of options for the cache
     */
    public function __construct(ContainerInterface $container, array $options)
    {
        $this->logger = $container->get('logger');
        $this->container = $container;
        $this->setOptions($options);
    }

    /**
     * Internal method that provides adapter specific cache writing logic
     *
     * @param string|int $id   The unique identifier of the data to cache
     * @param mixed      $data The actual data to cache
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function internalWrite($id, $data);

    /**
     * Internal method that provides adapter specific cache reading logic
     *
     * @param string|int $id The unique identifier of the data to read
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function internalRead($id);

    /**
     * Internal method that provides adapter specific cache deleting logic
     *
     * @param string|int $id The unique identifier of the data to read
     *
     * @abstract
     *
     * @return mixed
     */
    abstract public function internalDelete($id);

    /**
     * Sets up the options for the cache
     *
     * @param mixed $options Either an instance of AbstractOptions or an array of options for the cache
     *
     * @return \Tickit\CacheBundle\Options\AbstractOptions;
     */
    protected function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * Gets the options object for this cache
     *
     * @return \Tickit\CacheBundle\Options\AbstractOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sanitizes an identifier so that is safe for use in the cache engine
     *
     * @param mixed $id The raw identifier
     *
     * @return string
     */
    protected function sanitizeIdentifier($id)
    {
        $sanitizer = new Sanitizer();

        return $sanitizer->sanitizeIdentifier($id);
    }
}