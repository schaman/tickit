<?php

namespace Tickit\UserBundle\Tests\Service\Avatar;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\UserBundle\Service\Avatar\AvatarService;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tickit\UserBundle\Service\Avatar\Adapter\GravatarAdapter;

/**
 * Tests for the user avatar service
 */
class ServiceTest extends WebTestCase
{
    /**
     * Current user used to supply an avatar aware interfaced object
     *
     * @var \Tickit\UserBundle\Service\Avatar\Entity\AvatarAwareInterface $currentUser
     */
    protected static $currentUser;

    /**
     * Test insecure Gravatar image URL build
     */
    public function testInsecureGravatarImageUrlBuild()
    {
        $adapter = $this->getGravatarAdapter();

        $user = static::$currentUser;
        $imageUrl = $adapter->getImageUrl($user, 28);
        $this->assertStringMatchesFormat('http://www.gravatar.com/avatar/%s?s=%d&d=mm', $imageUrl);
    }

    /**
     * Test secure Gravatar image URL build
     */
    public function testSecureGravatarImageUrlBuild()
    {
        $adapter = $this->getGravatarAdapter(true);

        $user = static::$currentUser;
        $imageUrl = $adapter->getImageUrl($user, 28);
        $this->assertStringMatchesFormat('https://secure.gravatar.com/avatar/%s?s=%d&d=mm', $imageUrl);
    }

    /**
     * Get the Gravatar adapter
     *
     * @param bool $secureConnection Should the request use HTTPS?
     *
     * @return GravatarAdapter
     */
    protected function getGravatarAdapter($secureConnection = false)
    {
        $service = $this->getService('Tickit\UserBundle\Service\Avatar\Adapter\GravatarAdapter', $secureConnection);

        $adapter = $service->getAdapter();
        $this->assertTrue($adapter instanceof GravatarAdapter);

        return $adapter;
    }

    /**
     * Gets an instance of the avatar service
     *
     * @param string $adapterClass     Class name for this service
     * @param bool   $secureConnection Should the request use HTTPS?
     *
     * @return \Tickit\UserBundle\Service\Avatar\AvatarService
     */
    protected function getService($adapterClass, $secureConnection = false)
    {
        $container = $this->createClient()->getContainer();

        $doctrine = $container->get('doctrine');
        $user = $this->loadUser($doctrine, 'mark');

        static::$currentUser = $user;

        $container->get('security.context')->setToken(
            new UsernamePasswordToken(
                $user, null, 'main', $user->getRoles()
            )
        );

        if ($secureConnection) {
            $request = Request::create('https://example.com/');
        } else {
            $request = new Request();
        }

        return new AvatarService($request, $adapterClass);
    }

    /**
     * Load a user from doctrine
     *
     * @param \Doctrine\Bundle\DoctrineBundle\RegistryInterface $doctrine
     * @param String                                            $username
     *
     * @return \Tickit\UserBundle\Entity\User
     */
    protected function loadUser($doctrine, $username)
    {
        return $doctrine
            ->getRepository('TickitUserBundle:User')
            ->findOneByUsername($username);
    }
}