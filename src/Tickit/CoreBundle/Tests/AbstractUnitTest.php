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
}
