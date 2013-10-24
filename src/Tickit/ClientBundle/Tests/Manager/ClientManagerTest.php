<?php

namespace Tickit\ClientBundle\Tests\Manager;

use Doctrine\ORM\NoResultException;
use Tickit\ClientBundle\Entity\Client;
use Tickit\ClientBundle\Manager\ClientManager;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * ClientManager tests
 *
 * @package Tickit\ClientBundle\Tests\Manager
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
        $this->clientRepo = $this->getMockBuilder('\Tickit\ClientBundle\Entity\Repository\ClientRepository')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->em = $this->getMockEntityManager();
        $this->eventDispatcher = $this->getMockBuilder(
            '\Tickit\CoreBundle\Event\Dispatcher\AbstractEntityEventDispatcher'
        )
        ->disableOriginalConstructor()
        ->getMock();
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
