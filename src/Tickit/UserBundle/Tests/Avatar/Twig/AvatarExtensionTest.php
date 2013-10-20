<?php

namespace Tickit\UserBundle\Tests\Avatar\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Avatar\AvatarService;
use Tickit\UserBundle\Avatar\Twig\AvatarExtension;

/**
 * Avatar service twig extension tests
 *
 * @author Mark Wilson <mark@89allport.co.uk>
 */
class AvatarExtensionTest extends AbstractUnitTest
{
    /**
     * Test the twig extension contains the relevant functions
     */
    public function testTwigExtension()
    {
        $avatarAdapter = $this->getMockForAbstractClass('Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface');

        $securityContext = $this->getMockSecurityContext();

        $twigExtension      = new AvatarExtension($avatarAdapter, $securityContext);
        $availableFunctions = $twigExtension->getFunctions();

        $this->assertInternalType('array', $availableFunctions);
        $this->assertEquals(1, count($availableFunctions));

        /** @var \Twig_SimpleFunction $myAvatarFunction */
        $myAvatarFunction = $availableFunctions[0];
        $this->assertInstanceOf('Twig_SimpleFunction', $myAvatarFunction);

        $this->assertEquals('my_avatar_url', $myAvatarFunction->getName());
    }
}
