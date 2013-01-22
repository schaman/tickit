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
            $umask = $this->getOptions()->getUmask();
            if (false === mkdir($dir, $umask, true)) {
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

        $tagFile = $this->buildTagFilePath($id);
        $deleted = unlink($fullPath) && $this->deleteIdFromMasterTagFile($id);

        if (file_exists($tagFile)) {
            $deleted &= unlink($tagFile);
        }

        return (bool) $deleted;
    }

    /**
     * {@inheritDoc}
     */
    public function addTags($id, array $tags)
    {
        $currentTags = $this->readTagFile($id);

        foreach ($tags as $tag) {
            if (in_array($tag, $currentTags)) {
                continue;
            }
            $currentTags[] = $tag;
        }

        return $this->writeTagFile($currentTags, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function findByTags(array $tags, $partialMatch = false)
    {
        $masterTags = $this->readMasterTagFile();
        $matchedIds = array();
        $returnData = array();

        foreach (array_keys($masterTags) as $tag) {
            if (in_array($tag, $tags)) {
                $matchedIds += $masterTags[$tag];
            }
        }

        foreach ($matchedIds as $id) {
            $returnData[$id] = $this->internalRead($id);
        }

        if (empty($returnData)) {
            return null;
        }

        return $returnData;
    }

    /**
     * {@inheritDoc}
     */
    public function removeByTags(array $tags, $partialMatch = false)
    {
        $masterTags = $this->readMasterTagFile();
        $matchedIds = array();
        $match = false;

        foreach (array_keys($masterTags) as $tag) {
            if (in_array($tag, $tags)) {
                $match = true;
                $matchedIds += $masterTags[$tag];
                unset($masterTags[$tag]);
            }
        }

        $success = true;
        if (false !== $match) {
            foreach ($matchedIds as $id) {
                $success &= $this->internalDelete($id);
            }
        }

        return (bool) $success;
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
     * Creates a tag file for a given cache key spec
     *
     * @param mixed $id The cache key to create a tag file for
     *
     * @return bool
     */
    protected function createTagFile($id = null)
    {
        $success = true;
        $initData = json_encode(array());

        if (!empty($id)) {
            $tagFile = $this->buildTagFilePath($id);
            if (!file_exists($tagFile)) {
                $success &= false !== file_put_contents($tagFile, $initData);
            }
        }

        $masterTagFile = $this->buildMasterTagFilePath();
        if (!file_exists($masterTagFile)) {
            $success &= false !== file_put_contents($masterTagFile, $initData);
        }

        return (bool) $success;
    }

    /**
     * Reads the contents of the tag file for a given cache key spec
     *
     * @param mixed $id The cache key to read tags for
     *
     * @return mixed
     */
    protected function readTagFile($id)
    {
        $tagFile = $this->buildTagFilePath($id);
        $this->createTagFile($id); //make sure the tag file exists before attempting to read

        $tagFileContent = file_get_contents($tagFile);

        return (array) json_decode($tagFileContent);
    }

    /**
     * Reads the contents of the master tag file
     *
     * @return mixed
     */
    protected function readMasterTagFile()
    {
        $masterTagFile = $this->buildMasterTagFilePath();

        if (!file_exists($masterTagFile)) {
            $this->createTagFile();
        }

        $masterTagFileContent = file_get_contents($masterTagFile);

        return json_decode($masterTagFileContent, true);
    }

    /**
     * Writes tags for a given cache key spec
     *
     * @param array $tags The new array of tags to write
     * @param mixed $id   [Optional] The cache key to write tags for, defaults to none
     *
     * @return bool
     */
    protected function writeTagFile(array $tags, $id = null)
    {
        $tagFile = $this->buildTagFilePath($id);
        $masterTagFile = $this->buildMasterTagFilePath();
        $success = true;

        $masterTags = $this->readMasterTagFile();
        foreach ($tags as $tag) {
            if (!isset($masterTags[$tag])) {
                $masterTags[$tag] = array();
            }
            if (null !== $id && !in_array($id, $masterTags)) {
                $masterTags[$tag][] = $id;
            }
        }

        $success &= false !== file_put_contents($masterTagFile, json_encode($masterTags), LOCK_EX);
        if (null !== $id) {
            $success &= false !== file_put_contents($tagFile, json_encode($tags), LOCK_EX);
        }

        return (bool) $success;
    }

    /**
     * Removes all occurrences of a cache key from the master tag file
     *
     * @param mixed $id The cache key to remove from the master tag file
     *
     * @return bool
     */
    protected function deleteIdFromMasterTagFile($id)
    {
        $masterTags = $this->readMasterTagFile();
        $masterTagFile = $this->buildMasterTagFilePath();

        foreach ($masterTags as $tagName => $tagData) {
            $key = array_search($id, $tagData);
            if (false !== $key) {
                unset ($tagData[$key]);
            }
            $masterTags[$tagName] = $tagData;
        }

        return file_put_contents($masterTagFile, json_encode($masterTags), LOCK_EX);
    }

    /**
     * Gets the full path to the tag file for a given cache key spec
     *
     * @param mixed $id The cache key to build the tag file path for
     *
     * @return string
     */
    protected function buildTagFilePath($id)
    {
        $directory = $this->buildDirectory(true);
        $tagFilePath = sprintf('%s/__%s.tags', $directory, $id);

        return $tagFilePath;
    }

    /**
     * Gets the full path for the master tag file in this namespace
     *
     * @return string
     */
    protected function buildMasterTagFilePath()
    {
        $directory = $this->buildDirectory(true);
        $tagFilePath = sprintf('%s/__tags.meta', $directory);

        return $tagFilePath;
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

        return sprintf('%s/%s', $basePath, implode(DIRECTORY_SEPARATOR, $folders));
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

        if (is_object($data) || is_array($data)) {
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