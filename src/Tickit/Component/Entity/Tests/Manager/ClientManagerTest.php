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

namespace Tickit\Component\Entity\Tests\Manager;

use Doctrine\ORM\NoResultException;
use Tickit\Bundle\ClientBundle\Entity\Client;
use Tickit\Component\Entity\Manager\ClientManager;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;

/**
 * ClientManager tests
 *
 * @package Tickit\Component\Entity\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ClientManagerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $clientRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->clientRepo = $this->getMockBuilder('\Tickit\Bundle\ClientBundle\Entity\Repository\ClientRepository')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->em = $this->getMockEntityManager();
        $this->eventDispatcher = $this->getMockBuilder(
            '\Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher'
        )
        ->disableOriginalConstructor()
        ->getMockForAbstractClass();
    }
    
    /**
     * Tests the getRepository() method
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $this->assertSame($this->clientRepo, $this->getManager()->getRepository());
    }

    /**
     * Tests the find() method
     */
    public function testFindReturnsNullForInvalidClientId()
    {
        $this->clientRepo->expects($this->once())
                         ->method('find')
                         ->with(1)
                         ->will($this->throwException(new NoResultException()));

        $this->assertNull($this->getManager()->find(1));
    }

    /**
     * Tests the find() method
     */
    public function testFindReturnsClient()
    {
        $client = new Client();

        $this->clientRepo->expects($this->once())
                         ->method('find')
                         ->with(1)
                         ->will($this->returnValue($client));

        $this->assertEquals($client, $this->getManager()->find(1));
    }

    /**
     * Gets a new manager instance
     *
     * @return ClientManager
     */
    private function getManager()
    {
        return new ClientManager($this->clientRepo, $this->em, $this->eventDispatcher);
    }
}
