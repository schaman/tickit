<?php

namespace Tickit\UserBundle\Tests\Decorator;

use Tickit\UserBundle\Decorator\UserEntityDisplayNameDecorator;
use Tickit\UserBundle\Entity\User;

/**
 * User entity display name decorator tests
 *
 * @package Tickit\UserBundle\Tests\Decorator
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityDisplayNameDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests decorate() method on UserEntityDisplayNameDecorator
     */
    public function testDecoratorUserOutput()
    {
        $user = new User();
        $user->setForename('Joe')->setSurname('Bloggs');

        $decorator = new UserEntityDisplayNameDecorator();

        $this->assertEquals('Joe Bloggs', $decorator->decorate($user));
    }
}
