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

namespace Tickit\CoreBundle\Tests;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Tests\OrmTestCase;

/**
 * Abstract Orm Test
 *
 * @package Tickit\CoreBundle\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractOrmTest extends OrmTestCase
{
    /**
     * Gets an entity manager for testing
     *
     * @param string|array $paths      Namespace of entities for the annotation driver
     * @param array        $namespaces The namespaces to register on the entity manager
     *
     * @return EntityManager
     */
    protected function getEntityManager($paths, array $namespaces)
    {
        $reader = new AnnotationReader();
        $driver = new AnnotationDriver($reader, $paths);

        $em = $this->_getTestEntityManager();
        $em->getConfiguration()->setMetadataDriverImpl($driver);
        $em->getConfiguration()->setEntityNamespaces($namespaces);

        return $em;
    }
}
