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
     *
     * @return void
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName()
    {
        $filter = new ExactMatchFilter('invalid name', 'exact value');
        $em = $this->getMockEntityManager();
        $query = $this->getMockQueryBuilder();

        $this->trainQueryToReturnRootEntities($query);
        $this->trainQueryToReturnEntityManager($query, $em);
        $this->trainEntityManagerToReturnClassMetaData($em);

        $query->expects($this->never())
              ->method('getRootAliases');

        $query->expects($this->never())
              ->method('andWhere');

        $filter->applyToQuery($query);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @return void
     */
    public function testApplyToQueryAppliesFilterForValidKeyName()
    {
        $filter = new ExactMatchFilter('username', 'exact value');
        $em = $this->getMockEntityManager();
        $query = $this->getMockQueryBuilder();

        $this->trainQueryToReturnRootEntities($query);
        $this->trainQueryToReturnEntityManager($query, $em);

        $classMeta = new \stdClass();
        $classMeta->name = 'Tickit\Component\Model\User\User';

        $this->trainEntityManagerToReturnClassMetaData($em, $classMeta);

        $query->expects($this->once())
              ->method('getRootAliases')
              ->will($this->returnValue(array('u')));

        $query->expects($this->once())
              ->method('andWhere')
              ->with('u.username = :username')
              ->will($this->returnSelf());

        $query->expects($this->once())
              ->method('setParameter')
              ->with('username', 'exact value');

        $filter->applyToQuery($query);
    }
}
