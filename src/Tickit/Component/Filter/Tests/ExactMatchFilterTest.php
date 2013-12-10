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

use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Filter\ExactMatchFilter;

/**
 * ExactMatchFilter tests
 *
 * @package Tickit\Component\Filter\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ExactMatchFilterTest extends AbstractFilterTestCase
{
    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new ExactMatchFilter('invalid name', 'exact value');

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->query->expects($this->never())
                    ->method('andWhere');

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryDoesNotApplyFilterWithEmptyValue()
    {
        $filter = new ExactMatchFilter('username', '');

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->query->expects($this->never())
                    ->method('andWhere');

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryAppliesFilterForValidKeyName()
    {
        $filter = new ExactMatchFilter('username', 'exact value');

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->once())
                    ->method('getRootAliases')
                    ->will($this->returnValue(array('u')));

        $this->query->expects($this->once())
                    ->method('andWhere')
                    ->with('u.username = :username')
                    ->will($this->returnSelf());

        $this->query->expects($this->once())
                    ->method('setParameter')
                    ->with('username', 'exact value');

        $filter->applyToQuery($this->query);
    }
}
