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

namespace Tickit\Component\Issue\Tests\Listener;

use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Issue\Listener\IssueCreatedByListener;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\User\User;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueCreatedByListenerTest
 *
 * @package Tickit\Component\Issue\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueCreatedByListenerTest extends AbstractUnitTest
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
     * @dataProvider getOnIssueCreateFixtures
     */
    public function testOnIssueCreate($issue, $loggedInUser, $expectedIssue)
    {
        $token = $this->getMockUsernamePasswordToken();
        $token->expects($this->once())
              ->method('getUser')
              ->will($this->returnValue($loggedInUser));

        $this->securityContext->expects($this->once())
                              ->method('getToken')
                              ->will($this->returnValue($token));

        $this->getListener()->onIssueCreate(new EntityEvent($issue));
        $this->assertEquals($expectedIssue, $issue);
    }

    /**
     * @return array
     */
    public function getOnIssueCreateFixtures()
    {
        $user = new User();
        $user->setForename('Joe')
             ->setSurname('Bloggs');

        $issue = new Issue();
        $expectedIssue = new Issue();
        $expectedIssue->setCreatedBy($user);

        return [
            [$issue, $user, $expectedIssue]
        ];
    }

    /**
     * @return IssueCreatedByListener
     */
    private function getListener()
    {
        return new IssueCreatedByListener($this->securityContext);
    }
}
