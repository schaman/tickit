<?php

namespace Tickit\CoreBundle\Tests\Flash;

use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\CoreBundle\Flash\Provider;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * Tests for the flash message provider
 *
 * @package Tickit\CoreBundle\Tests\Flash
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProviderTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $session;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $flashBag;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->session = $this->getMockSession();
        $this->flashBag = $this->getMockForAbstractClass(
            '\Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface'
        );

        $this->session->expects($this->any())
                      ->method('getFlashBag')
                      ->will($this->returnValue($this->flashBag));
    }

    /**
     * Test to ensure the correct exception is thrown when no replacement value is provided
     *
     * @expectedException \RuntimeException
     */
    public function testCorrectExceptionThrownForEmptyReplacementValue()
    {
        $this->getProvider()->addEntityCreatedMessage('');
    }

    /**
     * Tests the getEntityCreatedMessage() method
     *
     * Ensures that a valid message string is returned
     *
     * @return void
     */
    public function testGetEntityCreatedMessage()
    {
        $this->flashBag->expects($this->once())
                      ->method('add')
                      ->with('notice', 'The team has been created successfully');

        $this->getProvider()->addEntityCreatedMessage('team');
    }

    /**
     * Tests the getEntityUpdatedMessage() method
     *
     * Ensures that a valid message string is returned
     *
     * @return void
     */
    public function testGetEntityUpdatedMessage()
    {
        $this->flashBag->expects($this->once())
                       ->method('add')
                       ->with('notice', 'The user has been updated successfully');

        $this->getProvider()->addEntityUpdatedMessage('user');
    }

    /**
     * Tests the getEntityDeletedMessage() method
     *
     * Ensures that a valid message string is returned
     *
     * @return void
     */
    public function testGetEntityDeletedMessage()
    {
        $this->flashBag->expects($this->once())
                       ->method('add')
                       ->with('notice', 'The project has been successfully deleted');

        $this->getProvider()->addEntityDeletedMessage('project');
    }

    /**
     * Gets a new provider instance
     *
     * @return Provider
     */
    private function getProvider()
    {
        return new Provider($this->session, __DIR__ . '/../../../../../app/config/extra/', 'test');
    }
}
