<?php

namespace Tickit\UserBundle\Tests\Service\Avatar;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use \Tickit\UserBundle\Service\Avatar\Twig\AvatarExtension;

class TwigExtensionTest extends WebTestCase
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