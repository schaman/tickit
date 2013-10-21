<?php

namespace Tickit\CoreBundle\Tests\Filters;

use Tickit\CoreBundle\Tests\AbstractUnitTest;

/**
 * Abstract Filter test case.
 *
 * Provides some helpers for tests on filters
 *
 * @package Tickit\CoreBundle\Tests\Filters
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AbstractFilterTestCase extends AbstractUnitTest
{
    protected function trainQueryToReturnRootEntities(\PHPUnit_Framework_MockObject_MockObject $query)
    {
        $query->expects($this->once())
              ->method('getRootEntities')
              ->will($this->returnValue(array('Tickit\UserBundle\Entity\User')));
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
        $classMeta->name = 'Tickit\UserBundle\Entity\User';

        $em->expects($this->once())
           ->method('getClassMetaData')
           ->with('Tickit\UserBundle\Entity\User')
           ->will($this->returnValue($classMeta));
    }
}