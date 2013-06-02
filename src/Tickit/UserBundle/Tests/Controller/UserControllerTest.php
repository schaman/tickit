<?php

namespace Tickit\UserBundle\Tests\Controller;

use Doctrine\DBAL\DBALException;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PermissionBundle\Entity\UserPermissionValue;
use Tickit\UserBundle\Entity\Group;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Manager\UserManager;

/**
 * UserController tests
 *
 * @package Tickit\UserBundle\Tests\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @group   functional
 */
class UserControllerTest extends AbstractFunctionalTest
{
    /**
     * Ensures that the user actions are not publicly accessible
     *
     * @return void
     */
    public function testUserActionsAreBehindFirewall()
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $client->request('get', $router->generate('user_index'));
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals('Login', $crawler->filter('h2')->text());
    }

    /**
     * Tests the indexAction()
     *
     * Ensures that any layout components are as we expect
     */
    public function testIndexActionLayout()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');
        /** @var UserManager $manager */
        $manager = $client->getContainer()->get('tickit_user.manager');
        $totalUsers = count($manager->getRepository()->findAll());

        $crawler = $client->request('get', $router->generate('user_index'));
        $this->assertEquals($totalUsers, $crawler->filter('div.data-list tbody tr')->count());
        $this->assertEquals('Manage Users', $crawler->filter('h2')->text());
    }

    /**
     * Tests the createAction()
     *
     * Ensures that a valid attempt to create a user is successful
     *
     * @return void
     */
    public function testCreateActionCreatesUserWithValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $router = $client->getContainer()->get('router');

        $container = static::createClient()->getContainer();
        $developersGroup = $container->get('doctrine')
                                     ->getRepository('TickitUserBundle:Group')
                                     ->findOneByName('Developers');

        $crawler = $client->request('get', $router->generate('user_index'));
        $totalUsers = $crawler->filter('div.data-list table tbody tr')->count();

        $crawler = $client->request('get', $router->generate('user_create'));
        $form = $crawler->selectButton('Save User')->form();
        $formValues = array(
            'tickit_user[forename]' => 'forename',
            'tickit_user[surname]' => 'surname',
            'tickit_user[username]' => 'user' . uniqid(),
            'tickit_user[email]' => sprintf('%s@googlemail.com', uniqid()),
            'tickit_user[group]' => $developersGroup->getId(),
            'tickit_user[password][first]' => 'somepassword',
            'tickit_user[password][second]' => 'somepassword'
        );
        $client->submit($form, $formValues);
        $crawler = $client->followRedirect();

        $count = $crawler->filter('div.flash-notice:contains("The user has been created successfully")')->count();
        $this->assertGreaterThan(0, $count);
        $this->assertEquals($totalUsers + 1, $crawler->filter('div.data-list table tbody tr')->count());
    }

    /**
     * Tests the createAction() method
     *
     * @return void
     */
    public function testCreateActionRendersEmptyPermissionsData()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $router = $container->get('router');

        $crawler = $client->request('get', $router->generate('user_create'));
        $this->assertEquals(2, $crawler->filter('div.data-list table tr')->count());
        $expectedMessage = 'You need to select a group for this user before you can edit permissions';
        $this->assertContains($expectedMessage, $client->getResponse()->getContent());
    }

    /**
     * Tests the editAction() method
     *
     * @return void
     */
    public function testEditActionRendersPermissionsData()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $router = $container->get('router');

        $user = $container->get('tickit_user.manager')->findUserByUsername('james');
        $crawler = $client->request('get', $router->generate('user_edit', array('id' => $user->getId())));
        $this->assertGreaterThan(2, $crawler->filter('div.data-list table tr')->count());
    }

    /**
     * Tests the editAction()
     *
     * Ensures that a valid attempt to update a user is successful
     *
     * @return void
     */
    public function testEditActionUpdatesUserWithValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $router = $container->get('router');

        $group = $container->get('doctrine')
                           ->getRepository('TickitUserBundle:Group')
                           ->findOneByName('Developers');

        $user = new User();
        $user->setForename('forename_123')
             ->setSurname('surname_123')
             ->setUsername('user' . uniqid())
             ->setEmail(sprintf('%s@email.com', uniqid()))
             ->setPassword('password')
             ->setGroup($group);

        $user = $client->getContainer()->get('tickit_user.manager')->create($user);

        $crawler = $client->request('get', $router->generate('user_edit', array('id' => $user->getId())));
        $form = $crawler->selectButton('Save Changes')->form();
        $newUsername = 'user' . uniqid();
        $newEmail = sprintf('%s@mail.com', uniqid());
        $formValues = array(
            'tickit_user[username]' => $newUsername,
            'tickit_user[forename]' => 'forename_12345',
            'tickit_user[surname]' => 'surname_12345',
            'tickit_user[email]' => $newEmail,
            'tickit_user[password][first]' => 'password',
            'tickit_user[password][second]' => 'password'
        );
        $crawler = $client->submit($form, $formValues);

        $count = $crawler->filter('div.flash-notice:contains("The user has been updated successfully")')->count();
        $this->assertGreaterThan(0, $count);
        $this->assertEquals($newUsername, $crawler->filter('input[name="tickit_user[username]"]')->attr('value'));
        $this->assertEquals($newEmail, $crawler->filter('input[name="tickit_user[email]"]')->attr('value'));
        $this->assertEquals('forename_12345', $crawler->filter('input[name="tickit_user[forename]"]')->attr('value'));
        $this->assertEquals('surname_12345', $crawler->filter('input[name="tickit_user[surname]"]')->attr('value'));
    }
}
