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

namespace Tickit\Bundle\IssueBundle\Tests\Form\EventListener;

use Tickit\Bundle\IssueBundle\Form\EventListener\CommentCreatedByFormSubscriber;
use Tickit\Component\Model\Issue\Comment;
use Tickit\Component\Model\User\User;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * CommentCreatedByFormSubscriberTest tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CommentCreatedByFormSubscriberTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->securityContext = $this->getMockSecurityContext();
    }

    /**
     * @dataProvider getCreatedByFixtures
     */
    public function testSetCreatedBy($formData, User $expectedCreatedBy = null, Comment $originalFormData = null)
    {
        $formEvent = $this->getMockFormEvent();
        $formEvent->expects($this->once())
                  ->method('getData')
                  ->will($this->returnValue($formData));

        $form = $this->getMockForm();
        $form->expects($this->once())
             ->method('getData')
             ->will($this->returnValue($originalFormData));

        $formEvent->expects($this->once())
                  ->method('getForm')
                  ->will($this->returnValue($form));

        if (!empty($formData) && !$originalFormData instanceof Comment) {

            $token = $this->getMockUsernamePasswordToken();
            $token->expects($this->once())
                  ->method('getUser')
                  ->will($this->returnValue($expectedCreatedBy));

            $this->securityContext->expects($this->once())
                                  ->method('getToken')
                                  ->will($this->returnValue($token));

            $expectedData = $formData;
            $expectedData['createdBy'] = $expectedCreatedBy;

            $formEvent->expects($this->once())
                      ->method('setData')
                      ->with($expectedData);
        }

        $this->getSubscriber()->setCreatedBy($formEvent);
    }

    /**
     * @return array
     */
    public function getCreatedByFixtures()
    {
        $user = new User();
        $user->setUsername('james');

        return [
            [null, null],
            [[], null],
            [['message' => 'hello', 'issue' => 15], $user],
            [['message' => 'hello', 'issue' => 15], $user, new Comment()]
        ];
    }

    /**
     * @return CommentCreatedByFormSubscriber
     */
    private function getSubscriber()
    {
        return new CommentCreatedByFormSubscriber($this->securityContext);
    }
}
