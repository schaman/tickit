<?php

namespace Tickit\Component\Image\Selector;

use Symfony\Component\Finder\Finder;

/**
 * Image selector that reads from the file system.
 *
 * @package Tickit\Component\Image\Selector
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FileSystemImageSelector implements ImageSelectorInterface
{
    /**
     * File path to the available images
     *
     * @var string
     */
    private $imagesFilePath;

    /**
     * Web path to the available images
     *
     * @var string
     */
    private $imagesWebPath;

    /**
     * Constructor
     *
     * @param string $imagesFilePath File path to the images
     * @param string $imagesWebPath  Web path to the images
     */
    public function __construct($imagesFilePath, $imagesWebPath)
    {
        $this->imagesFilePath = $imagesFilePath;
        $this->imagesWebPath  = $imagesWebPath;
    }

    /**
     * Selects a random image and returns its web path
     *
     * @throws \RuntimeException If the currently configured file path for the images is not readable
     *
     * @return string
     */
    public function select()
    {
        if (!is_readable($this->imagesFilePath)) {
            throw new \RuntimeException(
                sprintf(
                    'The provided file path (%s) for images is not readable, please make sure it exists',
                    $this->imagesFilePath
                )
            );
        }

        $finder = new Finder();
        $finder->in($this->imagesFilePath)
               ->files();

        $files = iterator_to_array($finder);
        /** @var \SplFileInfo $randomImage */
        $randomImage = $files[array_rand($files, 1)];

        return sprintf('%s/%s', $this->imagesWebPath, $randomImage->getFilename());
    }
}
