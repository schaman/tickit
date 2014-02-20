<?php

namespace Tickit\Component\Login\Background;

use Symfony\Component\Finder\Finder;

/**
 * Description
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FileSystemBackgroundSelector implements BackgroundSelectorInterface
{
    /**
     * File path to the background images
     *
     * @var string
     */
    private $imagesFilePath;

    /**
     * Web path to the background images
     *
     * @var string
     */
    private $imagesWebPath;

    /**
     * Constructor
     *
     * @param string $imagesFilePath File path to the background images
     * @param string $imagesWebPath  Web path to the background images
     */
    public function __construct($imagesFilePath, $imagesWebPath)
    {
        $this->imagesFilePath = $imagesFilePath;
        $this->imagesWebPath  = $imagesWebPath;
    }

    /**
     * Selects a random background image and returns its web path
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
                    'The provided file path (%s) for background images is not readable, please make sure it exists',
                    $this->imagesFilePath
                )
            );
        }

        $finder = new Finder();
        $finder->in($this->imagesFilePath)
               ->files();

        $files = iterator_to_array($finder);
        /** @var \SplFileInfo $randomBackground */
        $randomBackground = $files[array_rand($files, 1)];

        return sprintf('%s/%s', $this->imagesWebPath, $randomBackground->getFilename());
    }
}
