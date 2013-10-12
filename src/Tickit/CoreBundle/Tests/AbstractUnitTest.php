<?php

namespace Tickit\CoreBundle\Tests;

/**
 * Class AbstractUnitTest
 *
 * @package Tickit\CoreBundle\Tests
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
        return $this->getMockBuilder('\Tickit\CoreBundle\Controller\Helper\BaseHelper')
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
        return $this->getMockBuilder('\Tickit\CoreBundle\Controller\Helper\CsrfHelper')
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
        return $this->getMockBuilder('\Tickit\CoreBundle\Decorator\DomainObjectDecoratorInterface')
                    ->getMock();
    }

    /**
     * Returns a mock FilterCollectionBuilder
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockFilterCollectionBuilder()
    {
        return $this->getMockBuilder('\Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder')
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
        return $this->getMockBuilder('\Tickit\CoreBundle\Controller\Helper\FormHelper')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}
