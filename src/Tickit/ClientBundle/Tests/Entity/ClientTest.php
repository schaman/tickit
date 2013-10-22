<?php

namespace Tickit\ClientBundle\Tests\Entity\Client;

use Tickit\ClientBundle\Entity\Client;

/**
 * Client tests
 *
 * @package Tickit\ClientBundle\Tests\Entity\Client
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
    public function testStatusAcceptsValidStatus()
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
