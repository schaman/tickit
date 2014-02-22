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

namespace Tickit\Component\Image\Selector\Twig;

use Tickit\Component\Image\Selector\ImageSelectorInterface;

/**
 * Image selector twig extension.
 *
 * Provides twig function access to image selectors
 *
 * @package Tickit\Component\Image\Selector\Twig
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SelectorTwigExtension extends \Twig_Extension
{
    /**
     * An image selector
     *
     * @var ImageSelectorInterface
     */
    private $selector;

    /**
     * The twig method name that will be registered
     *
     * @var string
     */
    private $twigMethodName;

    /**
     * Construct.
     *
     * @param ImageSelectorInterface $selector       An image selector
     * @param string                 $twigMethodName The twig method name which will be registered against the given
     *                                               selector interface's select() method
     *
     * @throws \InvalidArgumentException If the $twigMethodName parameter is empty
     */
    public function __construct(ImageSelectorInterface $selector, $twigMethodName)
    {
        $this->selector       = $selector;
        $this->twigMethodName = $twigMethodName;

        if (empty($this->twigMethodName)) {
            throw new \InvalidArgumentException(
                sprintf('Argument 2 passed to %s must not be empty', __CLASS__)
            );
        }
    }

    /**
     * Gets registered twig functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction($this->twigMethodName, [$this->selector, 'select'])
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'tickit.image_locator';
    }
}
