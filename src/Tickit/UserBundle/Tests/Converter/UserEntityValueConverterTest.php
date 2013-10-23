<?php

namespace Tickit\UserBundle\Tests\Converter;

use Tickit\UserBundle\Converter\UserEntityValueConverter;
use Tickit\UserBundle\Entity\User;

/**
 * User converter tests
 *
 * @package Tickit\UserBundle\Tests\Converter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityValueConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $decorator;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->userManager = $this->getMockBuilder('\Tickit\UserBundle\Manager\UserManager')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->decorator = $this->getMockBuilder('\Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator')
                                ->disableArgumentCloning()
                                ->getMock();
    }

    /**
     * Tests convert() method
     *
     * Checks the display name output from user Id input
     *
     * @return void
     */
    public function testConvertDisplayNameOutput()
    {
        $user = new User();
        $user->setForename('Joe')->setSurname('Bloggs');

        $this->userManager->expects($this->once())
                          ->method('find')
                          ->with(123)
                          ->will($this->returnValue($user));

        $this->decorator->expects($this->once())
                        ->method('decorate')
                        ->with($user)
                        ->will($this->returnValue($user->getFullName()));

        $displayName = $this->getConverter()->convert(123);

        $this->assertEquals($user->getFullName(), $displayName);
    }

    /**
     * Tests the convert() method
     *
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testConvertThrowsExceptionWhenUserNotFound()
    {
        $this->userManager->expects($this->once())
                          ->method('find')
                          ->with(1)
                          ->will($this->returnValue(null));

        $this->getConverter()->convert(1);
    }

    /**
     * Gets a converter instance
     *
     * @return UserEntityValueConverter
     */
    private function getConverter()
    {
        return new UserEntityValueConverter($this->userManager, $this->decorator);
    }
}
