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

namespace Tickit\Component\Model\Tests\Issue;

use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Issue\IssueAttachment;
use Tickit\Component\Model\Project\Project;

/**
 * Issue tests
 *
 * @package Tickit\Component\Model\Tests\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the setPriority() method
     *
     * @dataProvider getPriorityFixtures
     */
    public function testSetPriorityAcceptsValidValues($priority)
    {
        $issue = new Issue();
        $issue->setPriority($priority);

        $this->assertEquals($priority, $issue->getPriority());
    }

    /**
     * @return array
     */
    public function getPriorityFixtures()
    {
        return [
            [Issue::PRIORITY_HIGH],
            [Issue::PRIORITY_NORMAL],
            [Issue::PRIORITY_LOW]
        ];
    }

    /**
     * Tests the setPriority() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetPriorityThrowsExceptionForInvalidValue()
    {
        $issue = new Issue();
        $issue->setPriority('invalid priority');
    }

    /**
     * Tests the addAttachment() method
     */
    public function testAddAttachmentAddsAttachment()
    {
        $issue = new Issue();
        $this->assertEquals(0, $issue->getAttachments()->count());

        $issue->addAttachment(new IssueAttachment())
              ->addAttachment(new IssueAttachment());

        $this->assertEquals(2, $issue->getAttachments()->count());
    }

    /**
     * Tests the getIssueNumber() method
     */
    public function testGetIssueNumber()
    {
        $project = new Project();
        $project->setIssuePrefix('TEST');

        $issue = new Issue();
        $issue->setProject($project)
              ->setNumber(12000);

        $issueNumber = $issue->getNumber();
        $this->assertInstanceOf('\Tickit\Component\Model\Issue\IssueNumber', $issueNumber);
        $this->assertEquals($project->getIssuePrefix(), $issueNumber->getPrefix());
    }
}
