<?php

namespace Tickit\UserBundle\Tests\Avatar\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\CoreBundle\Tests\AbstractUnitTest;
use Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface;
use Tickit\UserBundle\Avatar\AvatarService;
use Tickit\UserBundle\Avatar\Twig\AvatarExtension;
use Tickit\UserBundle\Entity\User;

/**
 * Avatar service twig extension tests
 *
 * @author Mark Wilson <mark@89allport.co.uk>
 */
class AvatarExtensionTest extends AbstractUnitTest
{
    /**
     * Avatar adapter
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $avatarAdapter;

    /**
     * Security context
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * Set up mock classes
     */
    public function setUp()
    {
        $this->avatarAdapter   = $this->getMockAvatarAdapter();
        $this->securityContext = $this->getMockSecurityContext();
    }

    /**
     * Tests getFunctions()
     */
    public function testTwigExtensionFunctions()
    {
        $twigExtension      = $this->getAvatarExtension();
        $availableFunctions = $twigExtension->getFunctions();

        $this->assertInternalType('array', $availableFunctions);
        $this->assertEquals(1, count($availableFunctions));

        /** @var \Twig_SimpleFunction $myAvatarFunction */
        $myAvatarFunction = $availableFunctions[0];
        $this->assertInstanceOf('Twig_SimpleFunction', $myAvatarFunction);

        $this->assertEquals('my_avatar_url', $myAvatarFunction->getName());
    }

    /**
     * Tests getCurrentUserAvatarImageUrl()
     */
    public function testCurrentUserAvatarImageUrl()
    {
        $user = new User();
        $user->setUsername('username');

        $token = $this->getMockUsernamePasswordToken();
        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue($user));

        $this->securityContext->expects($this->once())
             ->method('getToken')
             ->will($this->returnValue($token));

        $twigExtension = $this->getAvatarExtension();
        $imageUrl      = $twigExtension->getCurrentUserAvatarImageUrl(123);

        $this->assertEquals('', $imageUrl);
    }

    /**
     * Get avatar twig extension
     *
     * @return AvatarExtension
     */
    private function getAvatarExtension()
    {
        $avatarAdapter   = $this->avatarAdapter;
        $securityContext = $this->securityContext;

        $twigExtension = new AvatarExtension($avatarAdapter, $securityContext);

        return $twigExtension;
    }

    /**
     * Get a mock avatar adapter
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockAvatarAdapter()
    {
        return $this->getMockForAbstractClass('Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface');
    }
}
