<?php

namespace Tickit\CacheBundle\Options;

use Tickit\CacheBundle\Options\Exception\InvalidOptionException;

/**
 * Options resolver class for the File caching engine
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FileOptions extends AbstractOptions
{
    /**
     * The cache directory
     *
     * @var string $cacheDir
     */
    protected $cacheDir;

    /**
     * Sets the desired path to the cache file directory
     *
     * @param string $path The full working path to the cache directory
     *
     * @throws \Tickit\CacheBundle\Options\Exception\InvalidOptionException
     */
    public function setCacheDir($path)
    {
        if (!is_writable($path)) {
            throw new InvalidOptionException();
        }

        $this->cacheDir = $path;
    }

    /**
     * Gets the cache directory option
     *
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * Overrides abstract implementation and sets up engine specific options
     *
     * @param array $options The raw array of user defined options
     */
    protected function _resolveOptions(array $options)
    {
        if (!isset($options['cache_dir'])) {
            $options['cache_dir'] = '';
        }

        try {
            $this->setCacheDir($options['cache_dir']);
        } catch (InvalidOptionException $e) {
            $defaultCacheDir = $this->container->getParameter('tickit_cache.file.default_path');
            $this->setCacheDir($defaultCacheDir);
        }

        parent::_resolveOptions($options);
    }

}