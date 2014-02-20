<?php

namespace Tickit\Component\Image\Tests\Selector\Twig;

use Tickit\Component\Image\Selector\Twig\SelectorTwigExtension;

/**
 * SelectorTwigExtension tests
 *
 * @package Tickit\Component\Image\Tests\Selector\Twig
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class SelectorTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $selector;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->selector = $this->getMock('\Tickit\Component\Image\Selector\ImageSelectorInterface');
    }

    /**
     * Tests the getFunctions() method
     */
    public function testGetFunctions()
    {
        $functions = $this->getSelector()->getFunctions();

        $this->assertInternalType('array', $functions);
        $this->assertCount(1, $functions);

        /** @var \Twig_SimpleFunction $function */
        $function = array_shift($functions);
        $this->assertInstanceOf('\Twig_SimpleFunction', $function);
        $this->assertEquals('image_select', $function->getName());
        $this->assertEquals(array($this->selector, 'select'), $function->getCallable());
    }

    /**
     * Gets a new instance
     *
     * @return SelectorTwigExtension
     */
    private function getSelector()
    {
        return new SelectorTwigExtension($this->selector);
    }
}
