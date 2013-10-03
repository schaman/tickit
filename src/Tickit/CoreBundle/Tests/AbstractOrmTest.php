<?php

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
