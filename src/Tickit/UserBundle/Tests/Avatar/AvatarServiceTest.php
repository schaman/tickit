<?php

namespace Tickit\UserBundle\Tests\Avatar;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\UserBundle\Entity\User;
use Tickit\UserBundle\Avatar\AvatarService;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tickit\UserBundle\Avatar\Adapter\GravatarAdapter;
use Tickit\UserBundle\Avatar\Entity\AvatarAwareInterface;

/**
 * Tests for the user avatar service
 *
 * @author Mark Wilson <mark@89allport.co.uk>
 */
class AvatarServiceTest extends WebTestCase
{
    /**
     * Current user used to supply an avatar aware interfaced object
     *
     * @var AvatarAwareInterface
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
        $service = $this->getService('Tickit\UserBundle\Avatar\Adapter\GravatarAdapter', $secureConnection);

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
     * @return AvatarService
     */
    protected function getService($adapterClass, $secureConnection = false)
    {
        $container = $this->createClient()->getContainer();

        $user = $this->getUser();

        static::$currentUser = $user;

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $container->get('security.context')->setToken($token);

        if ($secureConnection) {
            $request = Request::create('https://example.com/');
        } else {
            $request = new Request();
        }

        return new AvatarService($request, $adapterClass);
    }

    /**
     * Gets a dummy user for testing the Avatar service
     *
     * @return User
     */
    protected function getUser()
    {
        $user = new User();
        $user->setEmail('mw870618@gmail.com');

        return $user;
    }
}
