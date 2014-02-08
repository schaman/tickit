<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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
use Symfony\Component\HttpKernel\KernelInterface;
use Tickit\Component\Entity\Manager\ClientManager;
use Tickit\Component\Entity\Manager\ProjectManager;
use Tickit\Component\Model\Client\Client;
use Tickit\Component\Model\Project\Project;
use Tickit\Component\Model\User\User;
use Tickit\Component\Entity\Manager\UserManager;
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
     * @Given /^the following users exist:$/
     */
    public function theFollowUsersExist(TableNode $users)
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
     * @Given /^the following clients exist:$/
     */
    public function theFollowingClientsExist(TableNode $clients)
    {
        foreach ($clients->getHash() as $clientData) {
            $this->createClient($clientData['name']);
        }
    }

    /**
     * @Given /^client "([^"]*)" has the following projects:$/
     */
    public function clientHasTheFollowingProjects($clientName, TableNode $projects)
    {
        /** @var ClientManager $clientManager */
        $clientManager = $this->getService('tickit_client.manager');
        $client = $clientManager->getRepository()->findOneBy(['name' => $clientName]);

        if (null === $client) {
            throw new \RuntimeException(
                sprintf('No client could be found with the name %s', $clientName)
            );
        }

        foreach ($projects->getHash() as $projectData) {
            $this->createProject($projectData['name'], $projectData['status'], $client);
        }
    }

    /**
     * Creates a user in the database for the context
     *
     * @param string       $email    The email address
     * @param string       $username The username
     * @param string       $password The password
     * @param string|array $roles    The user role(s) (defaults to ROLE_USER)
     * @param boolean      $enabled  True if this user is enabled, false otherwise
     *
     * @return User
     */
    public function createUser(
        $email,
        $username = 'username',
        $password = 'password',
        $roles = User::ROLE_DEFAULT,
        $enabled = true
    ) {
        $user = $this->getRepository('TickitUserBundle:User')->findOneByEmail($email);
        if (null !== $user) {
            return $user;
        }

        $roles = null === $roles ? User::ROLE_DEFAULT : $roles;
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $user = new User();
        $user->setEmail($email)
             ->setEnabled($enabled)
             ->setForename($this->faker->firstName)
             ->setSurname($this->faker->lastName)
             ->setPlainPassword($password)
             ->setUsername($username)
             ->setRoles($roles);

        /** @var UserManager $manager */
        $manager = $this->getService('fos_user.user_manager');
        $manager->create($user);

        return $user;
    }

    /**
     * Creates a new client in the database for the context.
     *
     * If one already exists with the same name, then it will not
     * create another.
     *
     * @param string $name   The client name
     * @param string $status The status of the client
     */
    private function createClient($name,$status = Client::STATUS_ACTIVE)
    {
        /** @var ClientManager $manager */
        $manager = $this->getService('tickit_client.manager');

        $client = new Client();
        $client->setName($name)
               ->setStatus($status)
               ->setNotes('Notes for ' . $name)
               ->setUrl('http://' . str_replace(' ', '', $name) . '.com');

        $manager->create($client);
    }

    /**
     * Creates a new project
     *
     * @param string $name   The project name
     * @param string $status The status of the project (optional, defaults to STATUS_ACTIVE)
     * @param Client $client The project client (optional, defaults to null)
     */
    private function createProject($name, $status = Project::STATUS_ACTIVE, Client $client = null)
    {
        /** @var ProjectManager $manager */
        $manager = $this->getService('tickit_project.manager');

        $project = new Project();
        $project->setName($name)
                ->setStatus($status)
                ->setClient($client)
                ->setTicketPrefix(substr($name, 0, 3))
                ->setOwner($this->getLoggedInUser());

        $manager->create($project);
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

    private function getLoggedInUser()
    {
        return $this->getMainContext()->getSubcontext('web-user')->loggedInUser;
    }
}