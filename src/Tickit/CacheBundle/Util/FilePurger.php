<?php

namespace Tickit\CacheBundle\Util;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use SplFileInfo;
use BadMethodCallException;

/**
 * Utility class that purges files from the file system
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class FilePurger
{
    /* @var string */
    protected $path;

    /**
     * Class constructor, sets up the current working path
     *
     * @param string $path The working path with which file purging will operate
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Sets the current path, helps when re-using the same FilePurger instance
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Gets the current path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }


    /**
     * Purges the contents of the current path
     *
     * @param bool $removePath [Optional] True to remove the current path as well as it's contents, defaults to false
     *
     * @throws BadMethodCallException If this method is called with no path currently set
     */
    public function purgeAll($removePath = false)
    {
        if (null === $this->path) {
            throw new BadMethodCallException(
                sprintf('You are trying to run purge with no $path set in %s on line %d', __CLASS__, __LINE__)
            );
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->path, RecursiveIteratorIterator::CHILD_FIRST)
        );

        /* @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($this->isDotFile($file)) {
                continue;
            }

            if ($file->isFile() || $file->isLink()) {
                unlink($file->getPathname());
            } else {
                rmdir($file->getPathname());
            }
        }

        if (true === $removePath) {
            rmdir($removePath);
            $this->path = null;
        }
    }

    /**
     * Returns true if the given file is a dot file ('..' or '.')
     *
     * @param \SplFileInfo $file The file instance to check
     *
     * @return bool
     */
    protected function isDotFile(SplFileInfo $file)
    {
        return in_array($file->getBasename(), array('..', '.'));
    }
}