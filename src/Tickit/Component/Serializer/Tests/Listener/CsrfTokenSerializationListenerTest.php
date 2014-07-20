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

use Symfony\Component\Security\Csrf\CsrfToken;
use Tickit\Component\Serializer\Listener\CsrfTokenSerializationListener;

/**
 * CsrfTokenSerializationListenerTest
 *
 * @package Tickit\Component\Serializer\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CsrfTokenSerializationListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $tokenManager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->tokenManager = $this->getMock('\Symfony\Component\Security\Csrf\CsrfTokenManagerInterface');
    }

    /**
     * @dataProvider getOnPostSerializeFixtures
     */
    public function testOnPostSerialize($object, $isValidObject)
    {
        $event = $this->getMockBuilder('\JMS\Serializer\EventDispatcher\ObjectEvent')
                      ->disableOriginalConstructor()
                      ->getMock();

        $visitor = $this->getMockBuilder('\JMS\Serializer\GenericSerializationVisitor')
                        ->disableOriginalConstructor()
                        ->getMock();

        $this->trainEventToReturnVisitor($event, $visitor);

        $event->expects($this->once())
              ->method('getObject')
              ->will($this->returnValue($object));

        if (true === $isValidObject) {

            $token = new CsrfToken($object->getId(), 'hello');

            $this->tokenManager->expects($this->once())
                               ->method('getToken')
                               ->with(get_class($object) . $object->getId())
                               ->will($this->returnValue($token));

            $visitor->expects($this->once())
                    ->method('addData')
                    ->with('csrfToken', $token->getValue());
        } else {
            $this->tokenManager->expects($this->never())
                               ->method('getToken');
        }

        $this->getListener()->onPostSerialize($event);
    }

    /**
     * @return array
     */
    public function getOnPostSerializeFixtures()
    {
        $validObject = $this->getMock('\Tickit\Component\Model\IdentifiableInterface');
        $validObject->expects($this->any())
                    ->method('getId')
                    ->will($this->returnValue(10));

        $invalidObject = new \stdClass();

        return [
            [$validObject, true],
            [$invalidObject, false]
        ];
    }

    public function testOnPostSerializeIgnoresInvalidVisitor()
    {
        $event = $this->getMockBuilder('\JMS\Serializer\EventDispatcher\ObjectEvent')
                      ->disableOriginalConstructor()
                      ->getMock();

        $visitor = new \stdClass();

        $this->trainEventToReturnVisitor($event, $visitor);

        $event->expects($this->never())
              ->method('getObject');

        $this->getListener()->onPostSerialize($event);
    }

    /**
     * @return CsrfTokenSerializationListener
     */
    private function getListener()
    {
        return new CsrfTokenSerializationListener($this->tokenManager);
    }

    private function trainEventToReturnVisitor(\PHPUnit_Framework_MockObject_MockObject $event, $visitor)
    {
        $event->expects($this->once())
              ->method('getVisitor')
              ->will($this->returnValue($visitor));
    }
}
