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

namespace Tickit\Bundle\CoreBundle\Tests\Filters;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;

/**
 * Abstract Filter test case.
 *
 * Provides some helpers for tests on filters
 *
 * @package Tickit\Bundle\CoreBundle\Tests\Filters
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
