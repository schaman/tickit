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

namespace Tickit\Bundle\PaginationBundle\Tests\Doctrine\Repository;

use Tickit\Bundle\PaginationBundle\Doctrine\Repository\PaginatedRepository;
use Tickit\Component\Pagination\Resolver\PageResolver;

/**
 * PaginatedRepository tests
 *
 * @package Tickit\Bundle\PaginationBundle\Tests\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PaginatedRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $classMeta;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->em = $this->getMock('\Doctrine\ORM\EntityManagerInterface');

        $this->classMeta = $this->getMockBuilder('\Doctrine\ORM\Mapping\ClassMetadata')
                                ->disableOriginalConstructor()
                                ->getMock();
    }
    
    /**
     * Tests the setPageResolver() method
     *
     * @expectedException \BadMethodCallException
     */
    public function testGetPageResolverThrowsExceptionWhenNoResolverSet()
    {
        $this->getRepository()->getPageResolver();
    }

    /**
     * Tests the getPageResolver() method
     */
    public function testGetPageResolverReturnsPageResolver()
    {
        $resolver = new PageResolver();
        $repo = $this->getRepository();

        $repo->setPageResolver($resolver);

        $this->assertSame($resolver, $repo->getPageResolver());
    }

    /**
     * Gets a repository instance
     *
     * @return PaginatedRepository
     */
    private function getRepository()
    {
        return new PaginatedRepository($this->em, $this->classMeta);
    }
}
