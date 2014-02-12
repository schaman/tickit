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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
use Tickit\Component\Filter\ExactMatchFilter;
use Tickit\Component\Model\User\User;

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

    public function testApplyToQueryAppliesArrayOfValues()
    {
        $entity1 = new User();
        $entity1->setId(1);
        $entity2 = new User();
        $entity2->setId(2);
        $values = new ArrayCollection([$entity1, $entity2]);
        $filter = new ExactMatchFilter('id', $values);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->once())
                    ->method('getRootAliases')
                    ->will($this->returnValue(['u']));

        $expression = new Expr();
        $this->query->expects($this->once())
                    ->method('expr')
                    ->will($this->returnValue($expression));

        $this->query->expects($this->once())
                    ->method('andWhere')
                    ->with($expression->in('u.id', [':id1', ':id2']))
                    ->will($this->returnSelf());

        $this->query->expects($this->exactly(2))
                    ->method('setParameter')
                    ->will($this->onConsecutiveCalls($this->returnSelf(), $this->returnSelf()));

        $this->query->expects($this->at(5))
                    ->method('setParameter')
                    ->with('id1', $entity1);

        $this->query->expects($this->at(6))
                    ->method('setParameter')
                    ->with('id2', $entity2);

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     */
    public function testApplyToQueryIgnoresEmptyArray()
    {
        $values = new ArrayCollection();
        $filter = new ExactMatchFilter('id', $values);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->once())
                    ->method('getRootAliases')
                    ->will($this->returnValue(['u']));

        $this->query->expects($this->never())
                    ->method('andWhere');

        $this->query->expects($this->never())
                    ->method('setParameter');

        $filter->applyToQuery($this->query);
    }
}
