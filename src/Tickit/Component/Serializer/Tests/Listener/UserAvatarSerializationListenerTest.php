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

namespace Tickit\Component\Serializer\Tests\Listener;

use Faker\Factory;
use Tickit\Component\Model\User\User;
use Tickit\Component\Serializer\Listener\UserAvatarSerializationListener;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * UserAvatarSerializationListener tests
 *
 * @package Tickit\Component\Serializer\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserAvatarSerializationListenerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $avatarAdapter;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->avatarAdapter = $this->getMockBuilder('\Tickit\Component\Avatar\Adapter\AvatarAdapterInterface')
                                    ->disableOriginalConstructor()
                                    ->getMock();
    }

    /**
     * @dataProvider getOnPostSerializeData
     */
    public function testOnPostSerialize(User $user, $avatarUrl)
    {
        $event = $this->getMockEvent();

        $visitor = $this->getMockBuilder('\JMS\Serializer\JsonSerializationVisitor')
                        ->disableOriginalConstructor()
                        ->getMock();

        $event->expects($this->once())
              ->method('getObject')
              ->will($this->returnValue($user));

        $this->avatarAdapter->expects($this->once())
                            ->method('getImageUrl')
                            ->with($user, 35)
                            ->will($this->returnValue($avatarUrl));

        $visitor->expects($this->once())
                ->method('addData')
                ->with('avatarUrl', $avatarUrl);

        $this->trainEventToReturnVisitor($event, $visitor);

        $this->getListener()->onPostSerialize($event);
    }

    public function getOnPostSerializeData()
    {
        $faker = Factory::create();

        return [
            [new User(), $faker->url]
        ];
    }

    public function testOnPostSerializeIgnoresInvalidVisitor()
    {
        $visitor = $this->getMockBuilder('\JMS\Serializer\AbstractVisitor')
                        ->disableOriginalConstructor()
                        ->getMock();

        $event = $this->getMockEvent();
        $this->trainEventToReturnVisitor($event, $visitor);

        $visitor->expects($this->never())
                ->method('addData');

        $this->avatarAdapter->expects($this->never())
                            ->method('getImageUrl');

        $this->getListener()->onPostSerialize($event);
    }

    /**
     * @return UserAvatarSerializationListener
     */
    private function getListener()
    {
        return new UserAvatarSerializationListener($this->avatarAdapter);
    }

    private function trainEventToReturnVisitor(\PHPUnit_Framework_MockObject_MockObject $event, $visitor)
    {
        $event->expects($this->once())
              ->method('getVisitor')
              ->will($this->returnValue($visitor));
    }

    private function getMockEvent()
    {
        $event = $this->getMockBuilder('\JMS\Serializer\EventDispatcher\ObjectEvent')
                      ->disableOriginalConstructor()
                      ->getMock();

        return $event;
    }
}
