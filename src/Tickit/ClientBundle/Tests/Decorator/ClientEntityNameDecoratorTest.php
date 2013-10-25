<?php

namespace Tickit\ClientBundle\Tests\Decorator;

use Tickit\ClientBundle\Decorator\ClientEntityNameDecorator;
use Tickit\ClientBundle\Entity\Client;

/**
 * ClientEntityNameDecorator tests
 *
 * @package Tickit\ClientBundle\Tests\Decorator
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientEntityNameDecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the decorate() method
     */
    public function testDecorateCorrectlyDecoratesClient()
    {
        $client = new Client();
        $client->setName('expected name');

        $this->assertEquals($client->getName(), $this->getDecorator()->decorate($client));
    }

    /**
     * Tests the decorate() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testDecorateThrowsExceptionForInvalidInstanceType()
    {
        $this->getDecorator()->decorate('invalid type');
    }

    /**
     * Gets a new decorator instance
     *
     * @return ClientEntityNameDecorator
     */
    private function getDecorator()
    {
        return new ClientEntityNameDecorator();
    }
}
 