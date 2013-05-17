<?php

namespace Tickit\PermissionBundle\Tests\Controller;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
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
     * {@inheritDoc}
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $container = static::createClient()->getContainer();
        $group = $container->get('doctrine')
                           ->getRepository('TickitUserBundle:Group')
                           ->findOneByName('Developers');

        static::$developersGroup = $group;
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionRendersPermissionListForEmptyUserId()
    {
        $this->markTestSkipped('Need to update for permission controller');

        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $route = $container->get('router')->generate('user_permissions_form_list', array('groupId' => static::$developersGroup->getId()));

        $crawler = $client->request('get', $route);
        $this->assertGreaterThan(2, $crawler->filter('table tr')->count());
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionReturns404ForInvalidUserId()
    {
        $this->markTestSkipped('Need to update for permission controller');

        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $route = $router->generate('user_permissions_form_list', array('groupId' => static::$developersGroup->getId(), 'userId' => 999999));

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
        $this->markTestSkipped('Need to update for permission controller');

        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        $route = $router->generate('user_permissions_form_list', array('groupId' => 999999));

        $crawler = $client->request('get', $route);
        $this->assertEquals(2, $crawler->filter('table tr')->count());
    }

    /**
     * Tests the permissionFormListAction() method
     *
     * @return void
     */
    public function testPermissionFormListActionRendersCorrectPermissionListForUserOverriddenPermissions()
    {
        $this->markTestSkipped('Need to update for permission controller');

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
        $route = $router->generate('user_permissions_form_list', array('groupId' => static::$developersGroup->getId(), 'userId' => $user->getId()));

        $crawler = $client->request('get', $route);
        $permissionRow = $crawler->filter('table tr[data-permission-id="' . $permission->getId() . '"]');
        $this->assertGreaterThan(0, $permissionRow->count());
        $this->assertGreaterThan(0, $permissionRow->filter('input[type="checkbox"]:nth-child(1):checked')->count());
        $userCheckbox = $permissionRow->filter('input[name="tickit_user[permissions][' . $permission->getId() . '][user]"]');
        $this->assertEmpty($userCheckbox->attr('checked'));
    }
}
