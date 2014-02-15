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

namespace Tickit\Component\Model\Tests\Client;

use Tickit\Component\Model\Client\Client;

/**
 * Client tests
 *
 * @package Tickit\Component\Model\Tests\Client
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the __construct() method
     */
    public function testConstructorSetsUpClientCorrectly()
    {
        $client = new Client();

        $this->assertEquals(Client::STATUS_ACTIVE, $client->getStatus());
        $this->assertEquals(0, $client->getTotalProjects());
    }

    /**
     * Tests the checkStatusIsValid() method
     */
    public function testCheckStatusIsValidReturnsFalseForInvalidStatus()
    {
        $this->assertFalse(Client::checkStatusIsValid('invalid status'));
    }

    /**
     * Tests the checkStatusIsValid() method
     */
    public function testCheckStatusIsValidReturnsTrueForValidStatus()
    {
        $this->assertTrue(Client::checkStatusIsValid(Client::STATUS_ARCHIVED));
    }

    /**
     * Tests the setStatus() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetStatusThrowsExceptionForInvalidStatus()
    {
        $client = new Client();
        $client->setStatus('invalid type');
    }

    /**
     * Tests the setStatus() method
     */
    public function testSetStatusAcceptsValidStatus()
    {
        $client = new Client();
        $client->setStatus(Client::STATUS_ARCHIVED);

        $this->assertEquals(Client::STATUS_ARCHIVED, $client->getStatus());
    }

    /**
     * Tests the incrementTotalProjects() method
     */
    public function testIncrementTotalProjectsCorrectlyIncrementsValue()
    {
        $client = new Client();

        $this->assertEquals(0, $client->getTotalProjects());
        $this->assertEquals(1, $client->incrementTotalProjects());
        $this->assertEquals(1, $client->getTotalProjects());
    }

    /**
     * Tests the decrementTotalProjects() method
     */
    public function testDecrementTotalProjectsCorrectlyDecrementsValue()
    {
        $client = new Client();

        $this->assertEquals(0, $client->getTotalProjects());
        $client->incrementTotalProjects();
        $this->assertEquals(1, $client->getTotalProjects());
        $client->decrementTotalProjects();
        $this->assertEquals(0, $client->getTotalProjects());
    }

    /**
     * Tests the decrementTotalProjects() method
     */
    public function testDecrementTotalProjectsDoesNotGoBelowZero()
    {
        $client = new Client();

        $this->assertEquals(0, $client->getTotalProjects());
        $this->assertEquals(0, $client->decrementTotalProjects());
        $this->assertEquals(0, $client->getTotalProjects());
    }
}
