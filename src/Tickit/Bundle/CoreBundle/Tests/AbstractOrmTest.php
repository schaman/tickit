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

namespace Tickit\Bundle\CoreBundle\Tests;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\Tests\OrmTestCase;

/**
 * Abstract Orm Test
 *
 * @package Tickit\Bundle\CoreBundle\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractOrmTest extends OrmTestCase
{
    /**
     * A replacement of Symfony\Bridge\Doctrine\Test\DoctrineTestHelper::createTestEntityManager();
     *
     * Creates and returns a dummy entity manager instance
     *
     * @param array $namespaces The namespaces for the entities managed by the entity manager
     *
     * @see \Symfony\Bridge\Doctrine\Test\DoctrineTestHelper
     *
     * @return EntityManager
     */
    public static function createTestEntityManager(array $namespaces = array())
    {
        $locator = new SymfonyFileLocator(static::getBundleEntityPaths(), '.orm.xml');

        $config = new Configuration();
        $config->setEntityNamespaces($namespaces);
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(\sys_get_temp_dir());
        $config->setProxyNamespace('SymfonyTests\Doctrine');
        $config->setMetadataDriverImpl(new XmlDriver($locator));
        $config->setQueryCacheImpl(new ArrayCache());
        $config->setMetadataCacheImpl(new ArrayCache());

        $params = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        return EntityManager::create($params, $config);
    }

    /**
     * Gets bundle entity paths to XML mapping
     *
     * @return array
     */
    protected static function getBundleEntityPaths()
    {
        $bundles = [
            'ClientBundle' => 'Tickit\Component\Model\Client',
            'PreferenceBundle' => 'Tickit\Component\Preference\Model',
            'ProjectBundle' => 'Tickit\Component\Model\Project',
            'NotificationBundle' => 'Tickit\Component\Notification\Model',
            'UserBundle' => 'Tickit\Component\Model\User',
            'TicketBundle' => 'Tickit\Component\Model\Ticket'
        ];

        $pathTemplate = __DIR__ . '/../../%s/Resources/config/doctrine';

        $paths = [];
        foreach ($bundles as $bundleName => $namespace) {
            $path = sprintf($pathTemplate, $bundleName);
            $paths[$path] = $namespace;
        }

        return $paths;
    }

    /**
     * Gets an entity manager for testing
     *
     * @param array $namespaces The namespaces to register on the entity manager
     *
     * @deprecated Use createTestEntityManager() instead
     *
     * @return EntityManager
     */
    protected function getEntityManager(array $namespaces)
    {
        return static::createTestEntityManager($namespaces);
    }
}
