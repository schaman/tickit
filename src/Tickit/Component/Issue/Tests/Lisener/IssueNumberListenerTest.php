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
use Tickit\Component\Issue\Listener\IssueNumberListener;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueNumberListener tests
 *
 * @package Tickit\Component\Issue\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueNumberListenerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $issueRepository;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->issueRepository = $this->getMock('\Tickit\Component\Entity\Repository\IssueRepositoryInterface');
    }

    /**
     * @dataProvider getIssueCreateFixtures
     */
    public function testOnIssueCreate($lastIssueNumber, $expectedIssueNumber)
    {
        $issue = new Issue();
        $project = new Project();
        $issue->setProject($project);
        $event = new EntityEvent($issue);

        $this->issueRepository->expects($this->once())
                              ->method('findLastIssueNumberForProject')
                              ->with($project)
                              ->will($this->returnValue($lastIssueNumber));

        $this->getListener()->onIssueCreate($event);

        $this->assertInstanceOf('Tickit\Component\Model\Issue\Issue', $event->getEntity());
        $this->assertEquals($expectedIssueNumber, $event->getEntity()->getNumber());
    }

    /**
     * @return array
     */
    public function getIssueCreateFixtures()
    {
        return [
            [10001, 10002],
            [1, 2],
            [null, IssueNumberListener::DEFAULT_ISSUE_NUMBER + 1]
        ];
    }

    /**
     * Gets a new listener
     *
     * @return IssueNumberListener
     */
    private function getListener()
    {
        return new IssueNumberListener($this->issueRepository);
    }
}
