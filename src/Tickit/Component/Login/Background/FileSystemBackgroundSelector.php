<?php

namespace Tickit\Component\Login\Background;

/**
 * Description
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class FileSystemBackgroundSelector implements BackgroundSelectorInterface
{
    public function __construct($imagePath)
    {

    }

    /**
     * Selects a random background image and returns its web path
     *
     * @return string
     */
    public function select()
    {
        // TODO: Implement select() method.
    }
}
