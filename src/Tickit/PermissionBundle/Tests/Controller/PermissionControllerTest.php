<?php

namespace Tickit\PermissionBundle\Tests\Controller;

use Doctrine\Common\Collections\Collection;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PermissionBundle\Entity\UserPermissionValue;
use Tickit\UserBundle\Entity\Group;

/**
 * Permission controller tests.
 *
 * @package Tickit\PermissionBundle\Tests\Controller
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @group   functional
 */
class PermissionControllerTest extends AbstractFunctionalTest
{
    /**
     * Sample user group.
     *
     * @var Group
     */
    protected static $developersGroup;

    /**
     * All system permissions
     *
     * @var array
     */
    protected static $permissions;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $container = static::createClient()->getContainer();
        static::$developersGroup = $container->get('doctrine')
                                             ->getRepository('TickitUserBundle:Group')
                                             ->findOneByName('Developers');

        static::$permissions = $container->get('doctrine')
                                         ->getRepository('TickitPermissionBundle:Permission')
                                         ->findAll();
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionRendersPermissionListForEmptyUserId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $route = $container->get('router')->generate('permissions_list', array('groupId' => static::$developersGroup->getId()));

        $client->request('get', $route);
        $response = json_decode($client->getResponse()->getContent());
        $this->assertInstanceOf('\stdClass', $response);
        $permissions = get_object_vars($response->permissions);
        $this->assertEquals(count(static::$permissions), count($permissions));
        $this->assertInstanceOf('\stdClass', array_shift($permissions));
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionReturns404ForInvalidUserId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $route = $router->generate('permissions_list', array('groupId' => static::$developersGroup->getId(), 'userId' => 999999));

        $client->request('get', $route);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionReturnsEmptyPermissionsForInvalidGroupId()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $route = $router->generate('permissions_list', array('groupId' => 999999));

        $client->request('get', $route);
        $response = json_decode($client->getResponse()->getContent());
        $this->assertEquals(1, count($response));
        $this->assertEquals('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', $response[0]->class);
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionRendersCorrectPermissionListForUserOverriddenPermissions()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $group = $container->get('fos_user.group_manager')->createGroup(uniqid('developers'));

        $user = static::$developer;
        $user->setUsername(uniqid('developer'))
             ->setPlainPassword('password')
             ->setEmail(uniqid() . '@googlemail.com')
             ->setForename('forename')
             ->setSurname('surname')
             ->setGroup($group);

        $user = $container->get('fos_user.user_manager')->create($user);

        $permission = $doctrine->getRepository('TickitPermissionBundle:Permission')
                               ->findOneBySystemName('users.view');

        $this->assertInstanceOf('Tickit\PermissionBundle\Entity\Permission', $permission);

        $permissionValue = new UserPermissionValue();
        $permissionValue->setValue(false)
                        ->setUser($user)
                        ->setPermission($permission);

        $doctrine->getManager()->persist($permissionValue);
        $doctrine->getManager()->flush();

        $router = $client->getContainer()->get('router');
        $route = $router->generate('permissions_list', array('groupId' => static::$developersGroup->getId(), 'userId' => $user->getId()));

        $client->request('get', $route);
        $response = json_decode($client->getResponse()->getContent());
        $permissions = get_object_vars($response->permissions);
        $this->assertEquals(count(static::$permissions), count($permissions));
        $this->assertFalse($permissions[$permission->getId()]->values->user);
        $this->assertTrue($permissions[$permission->getId()]->values->group);
    }
}
