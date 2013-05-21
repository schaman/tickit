<?php

namespace Tickit\PermissionBundle\Tests\Loader;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PermissionBundle\Entity\UserPermissionValue;
use Tickit\PermissionBundle\Loader\PermissionLoader;

/**
 * PermissionLoader tests
 *
 * @package Tickit\PermissionBundle\Tests\Loader
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class PermissionLoaderTest extends AbstractFunctionalTest
{
    /**
     * Tests the service container configuration
     *
     * @return void
     */
    public function testServiceContainerReturnsCorrectInstance()
    {
        $loader = static::createClient()->getContainer()->get('tickit_permission.loader');

        $this->assertInstanceOf('Tickit\PermissionBundle\Loader\PermissionLoader', $loader);
    }

    /**
     * Tests the loadForUser() method
     *
     * @return void
     */
    public function testLoadForUserLoadsCorrectPermissionsForUser()
    {
        $container = $this->getAuthenticatedClient(static::$admin)->getContainer();
        $doctrine = $container->get('doctrine');
        $loader = $container->get('tickit_permission.loader');

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $doctrine->getManager()->createQueryBuilder();
        $allPermissions = $doctrine->getRepository('TickitPermissionBundle:Permission')->findAll();
        $permissions = $queryBuilder->select('p')
                                    ->from('TickitPermissionBundle:Permission', 'p')
                                    ->where($queryBuilder->expr()->like('p.name', ':name'))
                                    ->setParameter('name', '%users%')
                                    ->getQuery()
                                    ->execute();

        $user = $this->createNewUser(true);
        $em = $doctrine->getManager();
        $user = $doctrine->getRepository('TickitUserBundle:User')->findOneByUsername($user->getUsername());

        foreach ($permissions as $permission) {
            $userValue = new UserPermissionValue();
            $userValue->setPermission($permission)
                      ->setUser($user)
                      ->setValue(false);

            $em->persist($userValue);
        }
        $em->flush();

        $loader->loadForUser($user);
        $sessionPermissions = $container->get('session')->get(PermissionLoader::SESSION_PERMISSIONS);

        $this->assertEquals(count($allPermissions) - count($permissions), count($sessionPermissions));

        foreach ($permissions as $permission) {
            $this->assertArrayNotHasKey($sessionPermissions, $permission->getSystemName());
        }
    }
}
