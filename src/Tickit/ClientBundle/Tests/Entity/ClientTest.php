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
}
