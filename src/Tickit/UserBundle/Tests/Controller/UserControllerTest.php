<?php

namespace Tickit\UserBundle\Tests\Controller;

use Doctrine\DBAL\DBALException;
use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\UserBundle\Controller\UserController;
use Tickit\UserBundle\Entity\User;

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
     * Tests the createAction()
     *
     * Ensures that a valid attempt to create a user is successful
     *
     * @return void
     */
    public function testCreateActionCreatesAdminUserWithValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $totalUsers = count($doctrine->getRepository('TickitUserBundle:User')->findAll());

        $newUsername = 'user' . uniqid();
        $crawler = $client->request('get', $this->generateRoute('user_create_form'));
        $form = $crawler->selectButton('Save User')->form();
        $formValues = array(
            'tickit_user[forename]' => 'forename',
            'tickit_user[surname]' => 'surname',
            'tickit_user[username]' => $newUsername,
            'tickit_user[email]' => sprintf('%s@googlemail.com', uniqid()),
            'tickit_user[password][first]' => 'somepassword',
            'tickit_user[password][second]' => 'somepassword',
            'tickit_user[admin]' => 1
        );
        $client->submit($form, $formValues);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));
        $this->assertEquals(++$totalUsers, count($doctrine->getRepository('TickitUserBundle:User')->findAll()));

        $createdUser = $doctrine->getRepository('TickitUserBundle:User')->findOneByUsername($newUsername);
        $this->assertInstanceOf('\Tickit\UserBundle\Entity\User', $createdUser);
        $this->assertTrue($createdUser->isAdmin());

        // tidy up created user
        $doctrine->getManager()->remove($createdUser);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the createAction()
     *
     * Ensures that a valid attempt to create a user is successful
     *
     * @return void
     */
    public function testCreateActionCreatesNonAdminUserWithValidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $totalUsers = count($doctrine->getRepository('TickitUserBundle:User')->findAll());

        $newUsername = 'user' . uniqid();
        $crawler = $client->request('get', $this->generateRoute('user_create_form'));
        $form = $crawler->selectButton('Save User')->form();
        $formValues = array(
            'tickit_user[forename]' => 'forename',
            'tickit_user[surname]' => 'surname',
            'tickit_user[username]' => $newUsername,
            'tickit_user[email]' => sprintf('%s@googlemail.com', uniqid()),
            'tickit_user[password][first]' => 'somepassword',
            'tickit_user[password][second]' => 'somepassword'
        );
        $client->submit($form, $formValues);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));
        $this->assertEquals(++$totalUsers, count($doctrine->getRepository('TickitUserBundle:User')->findAll()));

        $createdUser = $doctrine->getRepository('TickitUserBundle:User')->findOneByUsername($newUsername);
        $this->assertInstanceOf('\Tickit\UserBundle\Entity\User', $createdUser);
        $this->assertFalse($createdUser->isAdmin());

        // tidy up created user
        $doctrine->getManager()->remove($createdUser);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the createAction() method
     *
     * @return void
     */
    public function testCreateActionReturnsFormContentForInvalidDetails()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $crawler = $client->request('get', $this->generateRoute('user_create_form'));
        $form = $crawler->selectButton('Save User')->form();
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');

        $formValues = array(
            'tickit_user[forename]' => '',
            'tickit_user[surname]' => '',
            'tickit_user[username]' => '',
            'tickit_user[email]' => sprintf('%s@googlemail.com', uniqid()),
            'tickit_user[password][first]' => 'somepassword',
            'tickit_user[password][second]' => 'somepassword'
        );
        $client->submit($form, $formValues);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertFalse($response->success);
        $this->assertTrue(isset($response->form));
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
        $manager = $client->getContainer()->get('tickit_user.manager');
        $doctrine = $container->get('doctrine');

        $user = $manager->createUser();
        $user->setForename('forename_123')
             ->setSurname('surname_123')
             ->setUsername('user' . uniqid())
             ->setEmail(sprintf('%s@email.com', uniqid()))
             ->setPassword('password');

        $user = $manager->create($user);

        $newUsername = 'user' . uniqid();
        $newEmail = sprintf('%s@mail.com', uniqid());
        $crawler = $client->request('get', $this->generateRoute('user_edit_form', array('id' => $user->getId())));
        $form = $crawler->selectButton('Save Changes')->form(
            array(
                'tickit_user[username]' => $newUsername,
                'tickit_user[forename]' => 'forename_12345',
                'tickit_user[surname]' => 'surname_12345',
                'tickit_user[email]' => $newEmail,
                'tickit_user[password][first]' => 'password',
                'tickit_user[password][second]' => 'password'
            )
        );
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);
        $this->assertFalse(isset($response->form));

        $newUser = $doctrine->getRepository('TickitUserBundle:User')->findOneByUsername($newUsername);
        $doctrine->getManager()->refresh($newUser);

        /** @var User $newUser */
        $this->assertInstanceOf('\Tickit\UserBundle\Entity\User', $newUser);
        $this->assertEquals($newEmail, $newUser->getEmail());
        $this->assertEquals('forename_12345', $newUser->getForename());
        $this->assertEquals('surname_12345', $newUser->getSurname());

        $doctrine->getManager()->remove($newUser);
        $doctrine->getManager()->flush();
    }

    /**
     * Tests the deleteAction() method
     *
     * @return void
     */
    public function testDeleteActionReturns404ForInvalidToken()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $route = $this->generateRoute('user_delete', array('id' => static::$developer->getId(), 'token' => 'wadwadwa'));

        $client->request('post', $route);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Tests the deleteAction() method
     *
     * @return void
     */
    public function testDeleteActionDeletesUser()
    {
        $client = $this->getAuthenticatedClient(static::$admin);
        $container = $client->getContainer();
        $token = $container->get('form.csrf_provider')->generateCsrfToken(UserController::CSRF_DELETE_INTENTION);

        $user = $this->createNewUser(true);
        $route = $this->generateRoute('user_delete', array('id' => $user->getId(), 'token' => $token));

        $client->request('post', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent());
        $this->assertTrue($response->success);

        $nonExistentUser = $container->get('doctrine')->getRepository('TickitUserBundle:User')->find($user->getId());
        $this->assertNull($nonExistentUser);
    }
}
