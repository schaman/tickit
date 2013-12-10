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

namespace Tickit\Component\Filter\Tests;

use Tickit\Component\Filter\CallbackFilter;

/**
 * CallbackFilter tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CallbackFilterTest extends AbstractFilterTestCase
{
    /**
     * @var array
     */
    private $callable;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mock;

    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mock = $this->getMock('\Tickit\Component\Filter\Tests\Mock\CallableMock');
        $this->callable = [$this->mock, 'test'];
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidField()
    {
        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->mock->expects($this->never())
                   ->method('test');

        $filter = $this->getFilter('invalid field', 'value', $this->callable);
        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFilterForEmptyValue()
    {
        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->mock->expects($this->never())
                   ->method('test');

        $filter = $this->getFilter('username', '', $this->callable);
        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @expectedException \InvalidArgumentException
     */
    public function testApplyToQueryThrowsExceptionForInvalidCallable()
    {
        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $filter = $this->getFilter();
        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryAppliesValidFilter()
    {
        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->mock->expects($this->once())
                   ->method('test')
                   ->with($this->query, 'value');

        $filter = $this->getFilter('username', 'value', $this->callable);
        $filter->applyToQuery($this->query);
    }

    private function getFilter($field = 'username', $value = 'value', $callable = null)
    {
        return new CallbackFilter($field, $value, ['callback' => $callable]);
    }
}
