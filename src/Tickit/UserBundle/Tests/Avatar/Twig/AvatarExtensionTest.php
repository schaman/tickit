<?php

namespace Tickit\UserBundle\Tests\Avatar\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\UserBundle\Avatar\Twig\AvatarExtension;

/**
 * Avatar service twig extension tests
 *
 * @author Mark Wilson <mark@89allport.co.uk>
 */
class AvatarExtensionTest extends WebTestCase
{
    /**
     * Test the twig extension contains the relevant functions
     */
    public function testTwigExtension()
    {
        $container = $this->createClient()->getContainer();
        $securityContext = $container->get('security.context');

        $twigExtension = new AvatarExtension($container, $securityContext);
        $availableFunctions = $twigExtension->getFunctions();

        $this->assertInternalType('array', $availableFunctions);
        $this->assertEquals(1, count($availableFunctions));

        /** @var $myAvatarFunction \Twig_SimpleFunction */
        $myAvatarFunction = $availableFunctions[0];
        $this->assertInstanceOf('Twig_SimpleFunction', $myAvatarFunction);

        $this->assertEquals('my_avatar_url', $myAvatarFunction->getName());
    }
}
