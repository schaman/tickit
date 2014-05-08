<?php

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
        $event = $this->getMockBuilder('\JMS\Serializer\EventDispatcher\ObjectEvent')
                      ->disableOriginalConstructor()
                      ->getMock();

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

        $event->expects($this->once())
              ->method('getVisitor')
              ->will($this->returnValue($visitor));

        $this->getListener()->onPostSerialize($event);
    }

    public function getOnPostSerializeData()
    {
        $faker = Factory::create();

        return [
            [new User(), $faker->url]
        ];
    }

    /**
     * @return UserAvatarSerializationListener
     */
    private function getListener()
    {
        return new UserAvatarSerializationListener($this->avatarAdapter);
    }
}
