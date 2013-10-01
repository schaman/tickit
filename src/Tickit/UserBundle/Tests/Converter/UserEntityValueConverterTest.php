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
     * Tests convertUserIdToDisplayName()
     *
     * Checks the display name output from user Id input
     *
     * @return void
     */
    public function testConverterDisplayNameOutput()
    {
        $userManager = $this->getMockBuilder('Tickit\UserBundle\Manager\UserManager')
                            ->disableOriginalConstructor()
                            ->getMock();

        $user = new User();
        $user->setForename('Joe')->setSurname('Bloggs');

        $userManager->expects($this->once())
                    ->method('find')
                    ->will($this->returnValue($user));

        $converter = new UserEntityValueConverter($userManager);

        $displayName = $converter->convertUserIdToDisplayName(123);

        $this->assertEquals($user->getFullName(), $displayName);
    }
}
