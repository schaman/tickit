<?php

namespace Tickit\UserBundle\Tests\Avatar\Adapter;

use Tickit\UserBundle\Avatar\Adapter\GravatarAdapter;

/**
 * Test the Gravatar avatar adapter
 *
 * @package Tickit\UserBundle\Tests\Avatar\Adapter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class GravatarAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests getImageUrl()
     */
    public function testImageUrlGeneration()
    {
        $user = $this->getMockForAbstractClass('Tickit\UserBundle\Avatar\Entity\AvatarAwareInterface');
        $user->expects($this->once())
             ->method('getAvatarIdentifier')
             ->will($this->returnValue('avatar identifier'));

        $adapter = new GravatarAdapter();
        $url     = $adapter->getImageUrl($user, 28);

        $this->assertStringMatchesFormat('https://secure.gravatar.com/avatar/%s?s=28&d=mm', $url);
    }
}
