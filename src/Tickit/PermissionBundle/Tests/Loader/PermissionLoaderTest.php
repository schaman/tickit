<?php

namespace Tickit\PermissionBundle\Tests\Loader;

use Doctrine\ORM\QueryBuilder;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PermissionBundle\Entity\GroupPermissionValue;
use Tickit\PermissionBundle\Entity\UserPermissionValue;
use Tickit\PermissionBundle\Loader\PermissionLoader;
use Tickit\UserBundle\Entity\Group;

/**
 * PermissionLoader tests
 *
 * @package Tickit\PermissionBundle\Tests\Loader
 * @author  James Halsall <james.t.halsall@googlemail.com>
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
    public function testLoadForUserLoadsCorrectPermissionsForUserWithUserSpecificProhibits()
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

        // create a dummy administrator user (administrator group has ALL permissions granted by default)
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
            $this->assertArrayNotHasKey($permission->getSystemName(), $sessionPermissions);
        }
    }

    /**
     * Tests the loadForUser() method
     *
     * @return void
     */
    public function testLoadForUserLoadsCorrectPermissionsWithUserSpecificGrants()
    {
        $faker = $this->getFakerGenerator();
        $container = $this->getAuthenticatedClient(static::$admin)->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();
        $loader = $container->get('tickit_permission.loader');

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $doctrine->getManager()->createQueryBuilder();
        $allPermissions = $doctrine->getRepository('TickitPermissionBundle:Permission')->findAll();

        // create a dummy group
        $group = new Group('Group-' . $faker->sha1);
        $em->persist($group);
        $em->flush();

        // persist all permissions for the group with FALSE value
        foreach ($allPermissions as $perm) {
            $groupValue = new GroupPermissionValue();
            $groupValue->setGroup($group);
            $groupValue->setPermission($perm);
            $groupValue->setValue(false);

            $em->persist($groupValue);
        }
        $em->flush();

        $permissions = $queryBuilder->select('p')
                                    ->from('TickitPermissionBundle:Permission', 'p')
                                    ->where($queryBuilder->expr()->like('p.name', ':name'))
                                    ->setParameter('name', '%users%')
                                    ->getQuery()
                                    ->execute();

        // create a dummy user for the new group
        $user = $this->createNewUser(false)
                     ->setGroup($group);
        $em->persist($user);
        $em->flush();

        // check user has empty session permissions
        $loader->loadForUser($user);
        $sessionPermissions = $container->get('session')->get(PermissionLoader::SESSION_PERMISSIONS);
        $this->assertEmpty($sessionPermissions);

        // add some user level GRANTS
        foreach ($permissions as $permission) {
            $userValue = new UserPermissionValue();
            $userValue->setPermission($permission)
                      ->setUser($user)
                      ->setValue(true);

            $em->persist($userValue);
        }
        $em->flush();

        $loader->loadForUser($user);
        $newSessionPermissions = $container->get('session')->get(PermissionLoader::SESSION_PERMISSIONS);

        $this->assertEquals(count($permissions), count($newSessionPermissions));
    }
}
