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

namespace Tickit\Component\Filter\Tests;

use Tickit\Component\Test\AbstractUnitTest;
use Tickit\Component\Filter\Collection\FilterCollection;

/**
 * Abstract Filter test case.
 *
 * Provides some helpers for tests on filters
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractFilterTestCase extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $query;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $em;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->em = $this->getMockEntityManager();
        $this->query = $this->getMockQueryBuilder();
    }

    protected function trainQueryToReturnRootEntities(\PHPUnit_Framework_MockObject_MockObject $query)
    {
        $query->expects($this->once())
              ->method('getRootEntities')
              ->will($this->returnValue(array('Tickit\Component\Model\User\User')));
    }

    protected function trainQueryToReturnEntityManager(\PHPUnit_Framework_MockObject_MockObject $query, \PHPUnit_Framework_MockObject_MockObject $em)
    {
        $query->expects($this->once())
              ->method('getEntityManager')
              ->will($this->returnValue($em));
    }

    protected function trainEntityManagerToReturnClassMetaData(\PHPUnit_Framework_MockObject_MockObject $em)
    {
        $classMeta = new \stdClass();
        $classMeta->name = 'Tickit\Component\Model\User\User';

        $em->expects($this->once())
           ->method('getClassMetaData')
           ->with('Tickit\Component\Model\User\User')
           ->will($this->returnValue($classMeta));
    }

    public function getJoinTypeFixtures()
    {
        return [
            [FilterCollection::JOIN_TYPE_AND, 'andWhere'],
            [FilterCollection::JOIN_TYPE_OR, 'orWhere']
        ];
    }
}
