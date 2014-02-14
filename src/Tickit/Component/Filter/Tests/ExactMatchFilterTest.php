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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Expr;
use Tickit\Component\Filter\Collection\FilterCollection;
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
     *
     * @dataProvider getJoinTypeFixtures
     */
    public function testApplyToQueryDoesNotApplyFilterForInvalidKeyName($filterJoinType, $conditionMethod)
    {
        $filter = new ExactMatchFilter('invalid name', 'exact value', ['joinType' => $filterJoinType]);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->query->expects($this->never())
                    ->method($conditionMethod);

        $filter->applyToQuery($this->query, $filterJoinType);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @dataProvider getJoinTypeFixtures
     */
    public function testApplyToQueryDoesNotApplyFilterWithEmptyValue($filterJoinType, $conditionMethod)
    {
        $filter = new ExactMatchFilter('username', '', ['joinType' => $filterJoinType]);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->query->expects($this->never())
                    ->method($conditionMethod);

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @dataProvider getJoinTypeFixtures
     */
    public function testApplyToQueryAppliesFilterForValidKeyName($filterJoinType, $conditionMethod)
    {
        $filter = new ExactMatchFilter('username', 'exact value', ['joinType' => $filterJoinType]);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->once())
                    ->method('getRootAliases')
                    ->will($this->returnValue(array('u')));

        $this->query->expects($this->once())
                    ->method($conditionMethod)
                    ->with('u.username = :username')
                    ->will($this->returnSelf());

        $this->query->expects($this->once())
                    ->method('setParameter')
                    ->with('username', 'exact value');

        $filter->applyToQuery($this->query);
    }

    /**
     * Tests the applyToQuery() method
     *
     * @dataProvider getArrayFixtures
     */
    public function testApplyToQueryAppliesArrayOfValues($values, $filterJoinType, $conditionMethod)
    {
        $entities = ($values instanceof Collection) ? $values->toArray() : $values;
        $filter = new ExactMatchFilter('id', $values, ['joinType' => $filterJoinType]);

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

        $expectedOr = $expression->orX(
            $expression->eq('u.id', ':id0'),
            $expression->eq('u.id', ':id1')
        );
        $this->query->expects($this->once())
                    ->method($conditionMethod)
                    ->with($expectedOr)
                    ->will($this->returnSelf());

        $this->query->expects($this->exactly(2))
                    ->method('setParameter')
                    ->will($this->onConsecutiveCalls($this->returnSelf(), $this->returnSelf()));

        $this->query->expects($this->at(5))
                    ->method('setParameter')
                    ->with('id0', $entities[0]);

        $this->query->expects($this->at(6))
                    ->method('setParameter')
                    ->with('id1', $entities[1]);

        $filter->applyToQuery($this->query);
    }

    public function getArrayFixtures()
    {
        $entity1 = new User();
        $entity1->setId(1);
        $entity2 = new User();
        $entity2->setId(2);

        return [
            [new ArrayCollection([$entity1, $entity2]), FilterCollection::JOIN_TYPE_AND, 'andWhere'],
            [[$entity1, $entity2], FilterCollection::JOIN_TYPE_AND, 'andWhere'],
            [new ArrayCollection([$entity1, $entity2]), FilterCollection::JOIN_TYPE_OR, 'orWhere'],
            [[$entity1, $entity2], FilterCollection::JOIN_TYPE_OR, 'orWhere']
        ];
    }

    /**
     * Tests the applyToQuery() method
     *
     * @dataProvider getEmptyArrayFixtures
     */
    public function testApplyToQueryIgnoresEmptyArray($values, $filterJoinType, $conditionMethod)
    {
        $filter = new ExactMatchFilter('id', $values, ['joinType' => $filterJoinType]);

        $this->trainQueryToReturnRootEntities($this->query);
        $this->trainQueryToReturnEntityManager($this->query, $this->em);
        $this->trainEntityManagerToReturnClassMetaData($this->em);

        $this->query->expects($this->never())
                    ->method('getRootAliases');

        $this->query->expects($this->never())
                    ->method($conditionMethod);

        $this->query->expects($this->never())
                    ->method('setParameter');

        $filter->applyToQuery($this->query);
    }

    public function getEmptyArrayFixtures()
    {
        return [
            [new ArrayCollection(), FilterCollection::JOIN_TYPE_AND, 'andWhere'],
            [[], FilterCollection::JOIN_TYPE_AND, 'andWhere'],
            [new ArrayCollection(), FilterCollection::JOIN_TYPE_OR, 'orWhere'],
            [[], FilterCollection::JOIN_TYPE_OR, 'orWhere']
        ];
    }
}
