<?php

namespace Tickit\CacheBundle\Options;

use Tickit\CacheBundle\Options\Exception\InvalidOptionException;
use Tickit\CacheBundle\Util\Sanitizer;
use RuntimeException;

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
     * Boolean value indicating whether data should be automatically
     * serialized before caching
     *
     * @var bool $autoSerialize
     */
    protected $autoSerialize;

    /**
     * The name of the base directory below which the cache resides
     *
     * @var string $directoryBase
     */
    protected $directoryBase;

    /**
     * The default umask for newly created cache directories
     *
     * @var int $umask
     */
    protected $umask;

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
     * Sets the directory base name option
     *
     * @param string $name The name of the directory base
     *
     * @throws RuntimeException If the $name parameter appears to be a file path
     */
    public function setDirectoryBase($name)
    {
        $sanitizer = new Sanitizer();
        $sanitizedName = $sanitizer->sanitizePath($name);

        if (strpos($sanitizedName, DIRECTORY_SEPARATOR) !== false) {
            throw new RuntimeException(
                'An illegal directory base has been used (looks like a full path when it should be a name). Try changing your Tickit Cache config'
            );
        }

        $this->directoryBase = $name;
    }

    /**
     * Returns the current directory base value
     *
     * @return string
     */
    public function getDirectoryBase()
    {
        return $this->directoryBase;
    }


    /**
     * Returns true if the current options requires that serialization
     * of data should be automatic, false otherwise
     *
     * @return bool
     */
    public function getAutoSerialize()
    {
        return $this->autoSerialize;
    }

    /**
     * Sets the umask option
     *
     * @param int $umask The umask, e.g. 600
     */
    public function setUmask($umask)
    {
        $this->umask = (int) $umask;
    }

    /**
     * Gets the umask correctly prefixed with a zero
     *
     * @return string
     */
    public function getUmask()
    {
        return sprintf('0%d', $this->umask);
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
     */
    protected function _resolveOptions()
    {
        $cacheDir = $this->getRawOption('cache_dir', '');

        try {
            $this->setCacheDir($cacheDir);
        } catch (InvalidOptionException $e) {
            $defaultCacheDir = $this->container->getParameter('tickit_cache.file.default_path');
            $this->setCacheDir($defaultCacheDir);
        }

        $autoSerialize = $this->getRawOption('auto_serialize', null);
        if (is_bool($autoSerialize)) {
            $this->autoSerialize = $autoSerialize;
        } else {
            $this->autoSerialize = $this->container->getParameter('tickit_cache.file.auto_serialize');
        }

        $directoryBase = $this->getRawOption('directory_base', null);
        if (!empty($directoryBase)) {
            $this->setDirectoryBase($directoryBase);
        } else {
            $base = $this->container->getParameter('tickit_cache.file.directory_base');
            $this->setDirectoryBase($base);
        }

        $umask = $this->getRawOption('umask', null);
        if (is_numeric($umask)) {
            $this->setUmask($umask);
        } else {
            $defaultUmask = $this->container->getParameter('tickit_cache.file.umask');
            $this->setUmask($defaultUmask);
        }

        parent::_resolveOptions();
    }

}