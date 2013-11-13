<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Avatar\Tests\Twig;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Component\Avatar\Twig\AvatarExtension;
use Tickit\Bundle\UserBundle\Entity\User;

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
     * Tests getName()
     */
    public function testExtensionName()
    {
        $twigExtension = $this->getAvatarExtension();

        $this->assertEquals('tickit_user.avatar', $twigExtension->getName());
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
        return $this->getMockForAbstractClass('Tickit\Component\Avatar\Adapter\AvatarAdapterInterface');
    }
}
