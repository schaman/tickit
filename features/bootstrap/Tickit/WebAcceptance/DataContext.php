<?php

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

/**
 * Data context
 *
 * @package Tickit\WebAcceptance
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class DataContext extends BehatContext implements KernelAwareInterface
{
    /**
     * The application kernel
     *
     * @var KernelInterface
     */
    private $kernel;

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

    /**
     * Fetches a service from the container by its ID
     *
     * @param string $id The service ID to fetch
     *
     * @return object
     */
    private function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Gets the service container
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return $this->kernel->getContainer();
    }
}