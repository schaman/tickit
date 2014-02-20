<?php

namespace Tickit\Component\Image\Selector;

/**
 * Image selector interface.
 *
 * Image selectors are responsible for selecting a random image from
 * a source and returning it's web path.
 *
 * @package Tickit\Component\Image\Selector
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface ImageSelectorInterface
{
    /**
     * Selects a random image and returns its web path
     *
     * @return string
     */
    public function select();
}
