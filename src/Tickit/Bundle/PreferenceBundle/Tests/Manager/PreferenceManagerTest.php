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

namespace Tickit\Bundle\PreferenceBundle\Tests\Manager;

use Tickit\Bundle\PreferenceBundle\Manager\PreferenceManager;

/**
 * PreferenceManagerTest tests
 *
 * @package Tickit\Bundle\PreferenceBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The manager under test
     *
     * @var PreferenceManager
     */
    private $manager;

    /**
     * Setup
     */
    protected function setUp()
    {
        $repository = $this->getMockBuilder('Tickit\Bundle\PreferenceBundle\Entity\Repository\PreferenceRepository')
                           ->disableOriginalConstructor()
                           ->getMock();

        $this->manager = new PreferenceManager($repository);
    }

    /**
     * Tests the getRepository() method
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $repository = $this->manager->getRepository();

        $this->assertInstanceOf('Tickit\Bundle\PreferenceBundle\Entity\Repository\PreferenceRepository', $repository);
    }
}