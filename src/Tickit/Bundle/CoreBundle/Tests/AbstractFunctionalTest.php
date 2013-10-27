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

use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tickit\UserBundle\Entity\User;

/**
 * Abstract implementation of a functional test
 *
 * Provides common functionality for functional tests inside the application
 *
 * @package Tickit\Bundle\CoreBundle\Tests
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
abstract class AbstractFunctionalTest extends WebTestCase
{
    /**
     * Developer user entity used for testing
     *
     * @var User
     */
    protected static $developer;

    /**
     * Admin user entity used for testing
     *
     * @var User
     */
    protected static $admin;

    /**
     * Sets up user objects
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $doctrine = static::createClient()->getContainer()->get('doctrine');
        $userRepo = $doctrine->getRepository('TickitUserBundle:User');
        $developer = $userRepo->findOneByUsername('developer');
        $admin = $userRepo->findOneByUsername('james');

        $admin->setPlainPassword('password');
        $developer->setPlainPassword('password');

        static::$admin = $admin;
        static::$developer = $developer;
    }

    /**
     * Gets a client authenticated with a user
     *
     * @param User  $user    A user to authenticate with
     * @param array $options Array of options for the client
     * @param array $server  Array of server options for the client
     *
     * @return Client
     */
    protected function getAuthenticatedClient(User $user, array $options = array(), array $server = array())
    {
        $baseServer = array(
            'PHP_AUTH_USER' => $user->getUsername(),
            'PHP_AUTH_PW' => $user->getPlainPassword()
        );

        $server = $baseServer + $server;
        $client = $this->createClient($options, $server);
        $container = $client->getContainer();
        $session = $container->get('session');

        $token = new UsernamePasswordToken($user, $user->getPlainPassword(), 'main', $user->getRoles());

        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        $container->get('security.context')->setToken($token);

        return $client;
    }

    /**
     * Gets a faker generator instance
     *
     * @return Generator
     */
    protected function getFakerGenerator()
    {
        return Factory::create();
    }

    /**
     * Generates and returns a URL
     *
     * @param string $routeName      The name of the route to generate
     * @param array $routeParameters The route parameters (defaults to empty)
     *
     * @return string
     */
    protected function generateRoute($routeName, array $routeParameters = array())
    {
        $router = $this->createClient()->getContainer()->get('router');

        return $router->generate($routeName, $routeParameters);
    }

    /**
     * Creates a new user with fake details, and returns it for use
     *
     * @param boolean $persist Boolean value indicating whether to auto-persist this user, defaults to false
     * @param array   $options An array of options to create the user with
     *
     * @return User
     */
    protected function createNewUser($persist = false, array $options = array())
    {
        $faker = $this->getFakerGenerator();
        $container = static::createClient()->getContainer();

        if (!empty($options['roles'])) {
            $defaultRoles = $options['roles'];
        } else {
            $defaultRoles = array('ROLE_USER');
        }

        /** @var User $user */
        $user = $container->get('tickit_user.manager')->createUser();
        $user->setForename($faker->firstName)
             ->setSurname($faker->lastName)
             ->setEmail($faker->email)
             ->setUsername($user->getEmail())
             ->setPlainPassword($faker->md5);

        foreach ($defaultRoles as $role) {
            $user->addRole($role);
        }

        if (false !== $persist) {
            $em = $container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $user;
    }
}
