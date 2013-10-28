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

namespace Tickit\Bundle\CoreBundle\Tests\Controller\Helper;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Tickit\Bundle\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\UserBundle\Entity\User;

/**
 * BaseHelper tests
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Controller\Helper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class BaseHelperTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectDecorator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $objectCollectionDecorator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->request = $this->getMockBuilder('\Symfony\Component\HttpFoundation\Request')
                              ->disableOriginalConstructor()
                              ->getMock();

        $this->securityContext = $this->getMockForAbstractClass(
            '\Symfony\Component\Security\Core\SecurityContextInterface'
        );

        $this->objectDecorator = $this->getMockForAbstractClass(
            '\Tickit\Component\DomainObject\Decorator\DomainObjectDecoratorInterface'
        );

        $this->objectCollectionDecorator = $this->getMockForAbstractClass(
            '\Tickit\Component\DomainObject\Decorator\Collection\DomainObjectCollectionDecoratorInterface'
        );

        $this->router = $this->getMockRouter();
    }
    
    /**
     * Tests the getObjectDecorator() method
     */
    public function testGetObjectDecoratorReturnsCorrectInstance()
    {
        $this->assertSame($this->objectDecorator, $this->getHelper()->getObjectDecorator());
    }

    /**
     * Tests the getObjectCollectionDecorator() method
     */
    public function testGetObjectCollectionDecoratorReturnsCorrectInstance()
    {
        $this->assertSame($this->objectCollectionDecorator, $this->getHelper()->getObjectCollectionDecorator());
    }

    /**
     * Tests the getRequest() method
     */
    public function testGetRequestReturnsCorrectInstance()
    {
        $this->assertSame($this->request, $this->getHelper()->getRequest());
    }

    /**
     * Tests the getRouter() method
     */
    public function testGetRouterReturnsCorrectInstance()
    {
        $this->assertSame($this->router, $this->getHelper()->getRouter());
    }

    /**
     * Tests the generateUrl() method
     */
    public function testGenerateUrlGeneratesRoute()
    {
        $params = array('paremeter' => 'value');

        $this->router->expects($this->once())
                     ->method('generate')
                     ->with('route-name', $params)
                     ->will($this->returnValue('route'));

        $route = $this->getHelper()->generateUrl('route-name', $params);
        $this->assertEquals('route', $route);
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsNullForInvalidToken()
    {
        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue(null));

        $this->assertNull($this->getHelper()->getUser());
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsNullForInvalidUser()
    {
        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
                      ->disableOriginalConstructor()
                      ->getMock();

        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue(null));

        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue($token));

        $this->assertNull($this->getHelper()->getUser());
    }

    /**
     * Tests the getUser() method
     */
    public function testGetUserReturnsUser()
    {
        $token = $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
                              ->disableOriginalConstructor()
                              ->getMock();

        $user = new User();

        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue($user));

        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue($token));

        $this->assertSame($user, $this->getHelper()->getUser());
    }

    /**
     * Gets new BaseHelper instance
     *
     * @return BaseHelper
     */
    private function getHelper()
    {
        return new BaseHelper(
            $this->request,
            $this->securityContext,
            $this->objectDecorator,
            $this->objectCollectionDecorator,
            $this->router
        );
    }
}
