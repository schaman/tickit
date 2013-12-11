<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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


namespace Tickit\Component\Model\Tests\Project;

use Tickit\Component\Model\Project\Project;

/**
 * Project tests
 *
 * @package Tickit\Component\Model\Tests\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The project being tested
     *
     * @var Project
     */
    protected $sut;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->sut = new Project();
    }

    /**
     * Tests the constructor
     */
    public function testProjectInitialisesWithActiveStatus()
    {
        $this->assertEquals(Project::STATUS_ACTIVE, $this->sut->getStatus());
    }

    /**
     * Tests the setStatus() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetStatusThrowsExceptionForInvalidStatus()
    {
        $this->sut->setStatus('invalid');
    }

    /**
     * Tests the setStatus() method
     */
    public function testSetStatusAcceptsValidValue()
    {
        $this->sut->setStatus(Project::STATUS_ARCHIVED);

        $this->assertEquals(Project::STATUS_ARCHIVED, $this->sut->getStatus());
    }

    /**
     * Tests the getStatusTypes() method
     */
    public function testGetStatusTypesReturnsValidArray()
    {
        $statuses = Project::getStatusTypes();

        $this->assertContains(Project::STATUS_ARCHIVED, $statuses);
        $this->assertContains(Project::STATUS_ACTIVE, $statuses);
    }

    /**
     * Tests the getStatusTypes() method
     */
    public function testGetStatusTypesReturnsFriendlyNames()
    {
        $statuses = Project::getStatusTypes(true);

        $expected = [
            'active' => 'Active',
            'archived' => 'Archived'
        ];

        $this->assertEquals($expected, $statuses);
    }
}
