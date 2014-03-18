<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
