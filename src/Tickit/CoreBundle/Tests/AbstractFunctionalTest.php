<?php

namespace Tickit\CoreBundle\Tests;

use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Tickit\UserBundle\Entity\User;

/**
 * Abstract implementation of a functional test
 *
 * Provides common functionality for functional tests inside the application
 *
 * @package Tickit\CoreBundle\Tests
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
        $developer = new User();
        $developer->addRole(User::ROLE_DEFAULT)
             ->setUsername('developer')
             ->setPassword('password');

        $admin = new User();
        $admin->addRole(User::ROLE_SUPER_ADMIN)
              ->setUsername('james')
              ->setPassword('password');

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
            'PHP_AUTH_PW' => $user->getPassword()
        );

        $server = $baseServer + $server;
        $client = $this->createClient($options, $server);

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
}
