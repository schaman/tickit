<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Filters\ExactMatchFilter;
use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * ExactMatchFilter tests
 *
 * @package Tickit\CoreBundle\Tests\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ExactMatchFilterTest extends AbstractUnitTest
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

        $classMeta = new \stdClass();
        $classMeta->name = 'Tickit\UserBundle\Entity\User';

        $this->trainEntityManagerToReturnClassMetaData($em, $classMeta);

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
        $classMeta->name = 'Tickit\UserBundle\Entity\User';

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

    private function trainQueryToReturnRootEntities(\PHPUnit_Framework_MockObject_MockObject $query)
    {
        $query->expects($this->once())
              ->method('getRootEntities')
              ->will($this->returnValue(array('Tickit\UserBundle\Entity\User')));
    }

    private function trainQueryToReturnEntityManager(\PHPUnit_Framework_MockObject_MockObject $query, \PHPUnit_Framework_MockObject_MockObject $em)
    {
        $query->expects($this->once())
              ->method('getEntityManager')
              ->will($this->returnValue($em));
    }

    private function trainEntityManagerToReturnClassMetaData(\PHPUnit_Framework_MockObject_MockObject $em, \stdClass $classMeta)
    {
        $em->expects($this->once())
           ->method('getClassMetaData')
           ->with('Tickit\UserBundle\Entity\User')
           ->will($this->returnValue($classMeta));
    }
}
