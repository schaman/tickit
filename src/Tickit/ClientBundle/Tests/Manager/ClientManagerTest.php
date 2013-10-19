<?php

namespace Tickit\ClientBundle\Tests\Manager;

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
     * Gets a new manager instance
     *
     * @return ClientManager
     */
    private function getManager()
    {
        return new ClientManager($this->clientRepo, $this->em, $this->eventDispatcher);
    }
}
