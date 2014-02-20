<?php

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
     * Construct.
     *
     * @param ImageSelectorInterface $selector An image selector
     */
    public function __construct(ImageSelectorInterface $selector)
    {
        $this->selector = $selector;
    }

    /**
     * Gets registered twig functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('image_select', [$this->selector, 'select'])
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
