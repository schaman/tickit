<?php

namespace Tickit\PermissionBundle\Tests\Manager;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * Tests for PermissionManager
 *
 * @package Tickit\PermissionBundle\Tests\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionManagerTest extends AbstractFunctionalTest
{
    /**
     * Tests the getRepository() method
     *
     * @return void
     */
    public function testGetRepositoryReturnsCorrectInstance()
    {
        $container = static::createClient()->getContainer();
        $manager = $container->get('tickit_permission.manager');

        $repo = $manager->getRepository();

        $this->assertInstanceOf('Tickit\PermissionBundle\Entity\Repository\PermissionRepository', $repo);
    }

    /**
     * Tests the getUserPermissionValueRepository() method
     *
     * @return void
     */
    public function testGetUserPermissionValueRepositoryReturnsCorrectInstance()
    {
        $container = static::createClient()->getContainer();
        $manager = $container->get('tickit_permission.manager');

        $repo = $manager->getUserPermissionValueRepository();

        $this->assertInstanceOf('Tickit\PermissionBundle\Entity\Repository\UserPermissionValueRepository', $repo);
    }

    /**
     * Tests the getGroupPermissionValueRepository() method
     *
     * @return void
     */
    public function testGetGroupPermissionValueRepositoryReturnsCorrectInstance()
    {
        $container = static::createClient()->getContainer();
        $manager = $container->get('tickit_permission.manager');

        $repo = $manager->getGroupPermissionValueRepository();

        $this->assertInstanceOf('Tickit\PermissionBundle\Entity\Repository\GroupPermissionValueRepository', $repo);
    }
}
