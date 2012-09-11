<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Types\TaggableCacheInterface;
use Tickit\CacheBundle\Options\FileOptions;

/**
 * Caching engine for the file cache
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FileEngine extends AbstractEngine implements TaggableCacheInterface
{

    /* @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $container;

    /**
     * Class constructor, sets dependencies
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The dependency injection container
     * @param array                                                     $options   [Optional] An array of options for the cache
     */
    public function __construct(ContainerInterface $container, array $options = null)
    {
        $this->container = $container;
        $this->setOptions($options);
    }

    /**
     * {@inheritDoc}
     */
    public function internalWrite($id, $data)
    {
        //write data to file cache
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function addTags($id, array $tags)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function findByTags(array $tags, $partialMatch = false)
    {

    }

    /**
     * {@inheritDoc}
     */
    public function removeByTags(array $tags, $partialMatch = false)
    {

    }


    /**
     * {@inheritDoc}
     */
    protected function setOptions($options)
    {
        if (!$options instanceof FileOptions) {
            $options = new FileOptions($options, $this->container);
        }

        return parent::setOptions($options);
    }

}