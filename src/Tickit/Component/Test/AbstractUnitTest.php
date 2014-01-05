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

namespace Tickit\Component\Test;

/**
 * Class AbstractUnitTest
 *
 * @package Tickit\Component\Test
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Returns mock EntityManager
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEntityManager()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns mock SecurityContext
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSecurityContext()
    {
        return $this->getMockBuilder('\Symfony\Component\Security\Core\SecurityContext')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns mock UsernamePasswordToken
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockUsernamePasswordToken()
    {
        return $this->getMockBuilder('\Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns mock EventDispatcher
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockEventDispatcher()
    {
        return $this->getMockBuilder('\Symfony\Component\EventDispatcher\EventDispatcher')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock EngineInterface for templating
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockTemplateEngine()
    {
        return $this->getMockBuilder('\Symfony\Bundle\FrameworkBundle\Templating\EngineInterface')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock BaseHelper
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockBaseHelper()
    {
        return $this->getMockBuilder('\Tickit\Component\Controller\Helper\BaseHelper')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock CsrfHelper
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCsrfHelper()
    {
        return $this->getMockBuilder('\Tickit\Component\Controller\Helper\CsrfHelper')
                    ->disableOriginalConstructor()
                    ->getMock();
    }


    /**
     * Returns a mock object decorator
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockObjectDecorator()
    {
        return $this->getMockForAbstractClass('\Tickit\Component\Decorator\DomainObjectDecoratorInterface');
    }

    /**
     * Returns a mock object collection decorator
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockObjectCollectionDecorator()
    {
        return $this->getMockForAbstractClass(
            '\Tickit\Component\Decorator\Collection\DomainObjectCollectionDecoratorInterface'
        );
    }

    /**
     * Returns a mock FilterCollectionBuilder
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockFilterCollectionBuilder()
    {
        return $this->getMockBuilder('\Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock FormHelper
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockFormHelper()
    {
        return $this->getMockBuilder('\Tickit\Component\Controller\Helper\FormHelper')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock symfony Form
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockForm()
    {
        return $this->getMockBuilder('\Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock container
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockContainer()
    {
        return $this->getMockForAbstractClass('\Symfony\Component\DependencyInjection\ContainerInterface');
    }

    /**
     * Returns a mock Router
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockRouter()
    {
        return $this->getMockBuilder('\Symfony\Component\Routing\RouterInterface')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock QueryBuilder
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockQueryBuilder()
    {
        return $this->getMockBuilder('\Doctrine\ORM\QueryBuilder')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns a mock Session
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockSession()
    {
        return $this->getMockBuilder('\Symfony\Component\HttpFoundation\Session\Session')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Gets a mock RequestStack
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockRequestStack()
    {
        return $this->getMockBuilder('\Symfony\Component\HttpFoundation\RequestStack')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * Returns an instance of a non-accessible method.
     *
     * @param string $className  The class name
     * @param string $methodName The method name in the class
     *
     * @return \ReflectionMethod
     */
    protected static function getNonAccessibleMethod($className, $methodName)
    {
        $class = new \ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
