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
