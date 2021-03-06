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

namespace Tickit\Component\Decorator\Tests\Entity;

use Tickit\Component\Decorator\Entity\ClientEntityNameDecorator;
use Tickit\Component\Model\Client\Client;

/**
 * ClientEntityNameDecorator tests
 *
 * @package Tickit\Component\Decorator\Tests\Entity
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
