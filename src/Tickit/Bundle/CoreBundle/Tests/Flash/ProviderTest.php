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

namespace Tickit\Bundle\CoreBundle\Tests\Flash;

use Symfony\Component\HttpFoundation\Session\Session;
use Tickit\Bundle\CoreBundle\Flash\Provider;
use Tickit\Bundle\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;

/**
 * Tests for the flash message provider
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Flash
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
     * Tests the __construct() method
     */
    public function testProviderFallsBackToDefaultMessagesForInvalidEnvironment()
    {
        new Provider($this->session, __DIR__ . '/../../../../../../app/config/extra/', 'made_up');
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
        return new Provider($this->session, __DIR__ . '/../../../../../../app/config/extra/', 'test');
    }
}
