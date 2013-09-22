<?php

namespace Tickit\UserBundle\Tests\Converter;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * User converter tests
 *
 * @package Tickit\UserBundle\Tests\Converter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class UserEntityValueConverterTest extends AbstractFunctionalTest
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
        $container = $this->createClient()->getContainer();

        $converter = $container->get('tickit_user.user_converter');

        $user = $container->get('doctrine')->getRepository('TickitUserBundle:User')
                                           ->findOneByEmail('developer@gettickit.com');

        $displayName = $converter->convertUserIdToDisplayName($user->getId());

        $this->assertEquals($user->getFullName(), $displayName);
    }
}
