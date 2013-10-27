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

namespace Tickit\WebAcceptance;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Faker\Factory as FakerFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Manager\UserManager;
use Tickit\WebAcceptance\Mixins\ContainerMixin;

/**
 * Data context
 *
 * @package Tickit\WebAcceptance
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DataContext extends BehatContext implements KernelAwareInterface
{
    use ContainerMixin;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel HttpKernel instance
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^there are following users:$/
     */
    public function thereAreFollowingUsers(TableNode $users)
    {
        foreach ($users->getHash() as $userData) {
            $this->createUser(
                $userData['email'],
                $userData['username'],
                $userData['password'],
                isset($userData['role']) ? $userData['role'] : null,
                isset($userData['enabled']) ? $userData['enabled'] : true
            );
        }
    }

    /**
     * Creates a user in the database for the context
     *
     * @param string  $email    The email address
     * @param string  $username The username
     * @param string  $password The password
     * @param string  $role     The user role (defaults to ROLE_USER)
     * @param boolean $enabled  True if this user is enabled, false otherwise
     *
     * @return void
     */
    private function createUser($email, $username, $password, $role = User::ROLE_DEFAULT, $enabled = true)
    {
        $user = $this->getRepository('TickitUserBundle:User')->findOneByEmail($email);
        if (null !== $user) {
            return;
        }

        $role = null === $role ? User::ROLE_DEFAULT : $role;

        $user = new User();
        $user->setEmail($email)
             ->setEnabled($enabled)
             ->setForename($this->faker->firstName)
             ->setSurname($this->faker->lastName)
             ->setPlainPassword($password)
             ->setUsername($username)
             ->addRole($role);

        /** @var UserManager $manager */
        $manager = $this->getService('fos_user.user_manager');
        $manager->create($user);
    }

    /**
     * Gets a repository for a given entity name
     *
     * @param string $entityName The entity name of the repository to fetch
     *
     * @return ObjectRepository
     */
    private function getRepository($entityName)
    {
        return $this->getService('doctrine')
                    ->getManager()
                    ->getRepository($entityName);
    }
}