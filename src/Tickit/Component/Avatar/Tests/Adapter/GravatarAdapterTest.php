<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Avatar\Tests\Adapter;

use Tickit\Component\Avatar\Adapter\GravatarAdapter;

/**
 * Test the Gravatar avatar adapter
 *
 * @package Tickit\Component\Avatar\Tests\Adapter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class GravatarAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests getImageUrl()
     */
    public function testImageUrlGeneration()
    {
        $user = $this->getMockForAbstractClass('Tickit\Component\Avatar\Entity\AvatarAwareInterface');
        $user->expects($this->once())
             ->method('getAvatarIdentifier')
             ->will($this->returnValue('avatar identifier'));

        $adapter = new GravatarAdapter();
        $url     = $adapter->getImageUrl($user, 28);

        $this->assertStringMatchesFormat('https://secure.gravatar.com/avatar/%s?s=28&d=mm', $url);
    }
}
