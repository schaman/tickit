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

namespace Tickit\Component\Entity\Tests\Manager;

use Tickit\Component\Entity\Manager\IssueManager;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueManager tests
 *
 * @package Tickit\Component\Entity\Tests\Manager
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class IssueManagerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $issueRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityEventDispatcher;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->issueRepository = $this->getMockBuilder('\Tickit\Component\Entity\Repository\IssueRepositoryInterface')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->em = $this->getMockEntityManager();
        $this->entityEventDispatcher = $this->getMockBuilder(
                                               '\Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher'
                                            )
                                            ->disableOriginalConstructor()
                                            ->getMockForAbstractClass();
    }

    /**
     * Tests the getRepository() method
     */
    public function testGetRepository()
    {
        $this->assertSame($this->issueRepository, $this->getManager()->getRepository());
    }

    /**
     * Gets a new manager instance
     *
     * @return IssueManager
     */
    private function getManager()
    {
        return new IssueManager($this->issueRepository, $this->em, $this->entityEventDispatcher);
    }
}
