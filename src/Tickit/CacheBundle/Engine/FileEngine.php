<?php

namespace Tickit\CacheBundle\Engine;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tickit\CacheBundle\Types\TaggableCacheInterface;
use Tickit\CacheBundle\Options\FileOptions;
use Tickit\CacheBundle\Types\PurgeableCacheInterface;
use Tickit\CacheBundle\Util\FilePurger;

/**
 * Caching engine for the file cache
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FileEngine extends AbstractEngine implements TaggableCacheInterface, PurgeableCacheInterface
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
        $dir = $this->buildDirectory();
        $id = $this->sanitizeIdentifier($id);

        if (!file_exists($dir)) {
            if (false === mkdir($dir, 0766, true)) {
                throw new Exception\PermissionDeniedException(
                    sprintf('Permission denied creating %s in %s on line %s', $dir, __CLASS__, __LINE__)
                );
            }
        }

        $writeData = $this->prepareData($data);
        $written = file_put_contents(sprintf('%s/%s', $dir, $id), $writeData, LOCK_EX);

        if (false === $written) {
            throw new Exception\PermissionDeniedException(
                sprintf('Permission denied writing data (with identifier of %s) to %s in class %s on line %d', $id, $dir, __CLASS__, __LINE__)
            );
        }

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function internalRead($id)
    {
        $dir = $this->buildDirectory();
        $id = $this->sanitizeIdentifier($id);

        $fullPath = sprintf('%s/%s', $dir, $id);

        if (!is_readable($fullPath)) {
            return null;
        }

        $contents = file_get_contents($fullPath);

        return $this->unpackContents($contents);
    }

    /**
     * {@inheritDoc}
     */
    public function internalDelete($id)
    {
        $dir = $this->buildDirectory();
        $id = $this->sanitizeIdentifier($id);

        $fullPath = sprintf('%s/%s', $dir, $id);

        if (!is_writable($fullPath)) {
            throw new Exception\PermissionDeniedException(
                sprintf('Permission denied deleting data (with identified of %s) in class %s on line %s', $id, __CLASS__, __LINE__)
            );
        }

        $deleted = unlink($fullPath);

        return $deleted;
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
    public function purgeAll()
    {
        $cacheDir = $this->buildDirectory(false);
        $purger = new FilePurger($cacheDir);

        $purger->purgeAll();
    }

    /**
     * {@inheritDoc}
     */
    public function purgeNamespace($namespace)
    {
        $fullPath = $this->buildDirectory();
        $purger = new FilePurger($fullPath);

        $purger->purgeAll();
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

    /**
     * Builds a directory structure for the current cache based off the
     * options object
     *
     * @param bool $includeNamespace [Optional] False to ignore namespaces when building the directory, defaults to true
     *
     * @return string
     */
    protected function buildDirectory($includeNamespace = true)
    {
        $folders = array();
        $basePath = $this->getOptions()->getCacheDir();

        if (true === $includeNamespace) {
            $namespace = $this->getOptions()->getNamespace();
            $folders = explode('.', $namespace);
        }

        //TODO: make "tickit_cache" prefix customisable via config
        return sprintf('%s/tickit_cache/%s', $basePath, implode(DIRECTORY_SEPARATOR, $folders));
    }


    /**
     * Prepares a piece of data for writing to the file cache
     *
     * @param mixed $data The data to cache, if this data is an object then it will be serialized
     *                    (if auto_serialize is disabled an exception will be thrown)
     *
     * @throws \Tickit\CacheBundle\Engine\Exception\NotCacheableException
     *
     * @return string
     */
    protected function prepareData($data)
    {
        $autoSerialize = $this->getOptions()->getAutoSerialize();

        if (is_object($data)) {
            if (false === $autoSerialize) {
                throw new Exception\NotCacheableException(
                    sprintf(
                        'This data cannot be cached, it is an unserialized instance of %s and Tickit cache\'s auto serialize is disabled',
                        get_class($data)
                    )
                );
            }

            $data = serialize($data);
        }

        return $data;
    }

    /**
     * Unpacks a raw piece of data retrieved from the cache and returns it in a valid
     * format
     *
     * @param string $contents The raw string of data retrieved from the cache
     *
     * @return bool|string
     */
    protected function unpackContents($contents)
    {
        $unserializedContent = @unserialize($contents);

        //check for boolean false in serialized form
        if ('b:0;' === $contents) {
            return false;
        }

        if (false === $unserializedContent) {
            return $contents;
        }

        return $unserializedContent;
    }

}