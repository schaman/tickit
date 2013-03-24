<?php

namespace Tickit\PermissionBundle\Tests\Service;

use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tickit\PermissionBundle\Service\PermissionService;
use Tickit\CacheBundle\Cache\CacheFactory;
use Tickit\PermissionBundle\Entity\Permission;

/**
 * Tests for the permissions service
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class PermissionServiceTest extends WebTestCase
{
    /**
     * Static instance of the permissions service
     *
     * @var PermissionService
     */
    protected static $service;

    /**
     * Test to make sure that the permissions are correctly persisted to the session
     */
    public function testWriteToSession()
    {
        $service = $this->getService();
        $permissions = $this->getDummyPermissions();
        $service->writeToSession($permissions);
        $sessionPermissions = $service->getSession()->get(PermissionService::SESSION_PERMISSIONS);

        $this->assertInternalType('array', $sessionPermissions);
        $this->assertArrayHasKey('dummy-permission-one', $sessionPermissions);
        $this->assertArrayHasKey('dummy-permission-two', $sessionPermissions);
        $this->assertArrayHasKey('dummy-permission-three', $sessionPermissions);
        $this->assertArrayHasKey('dummy-permission-four', $sessionPermissions);
        $this->assertArrayHasKey('dummy-permission-five', $sessionPermissions);

        $this->assertEquals(1, $sessionPermissions['dummy-permission-one']);
        $this->assertEquals(2, $sessionPermissions['dummy-permission-two']);
        $this->assertEquals(3, $sessionPermissions['dummy-permission-three']);
        $this->assertEquals(4, $sessionPermissions['dummy-permission-four']);
        $this->assertEquals(5, $sessionPermissions['dummy-permission-five']);
    }

    /**
     * Test to make sure that the has() method in the permission service properly detects when
     * a user has a given permission
     */
    public function testHas()
    {
        $service = $this->getService();
        $permissions = $this->getDummyPermissions();
        $service->writeToSession($permissions);

        $this->assertTrue($service->has('dummy-permission-one'));
        $this->assertFalse($service->has('dummy-permission-fake'));
    }

    /**
     * Gets an instance of the Permissions service
     *
     * @return PermissionService
     */
    protected function getService()
    {
        if (null === static::$service) {
            $container = $this->createClient()->getContainer();
            $session = new Session(new MockArraySessionStorage());
            $session->setId('12345');
            $cacheFactory = new CacheFactory($container);
            static::$service = new PermissionService($session, $container->get('doctrine'), $cacheFactory);
        }

        return static::$service;
    }

    /**
     * Builds and returns an array of dummy permission objects for testing
     *
     * @return Permission[]
     */
    protected function getDummyPermissions()
    {
        $permissionObjects = array();
        $permissionNames = array(
            'dummy-permission-one' => '1',
            'dummy-permission-two' => '2',
            'dummy-permission-three' => '3',
            'dummy-permission-four' => '4',
            'dummy-permission-five' => '5'
        );

        foreach ($permissionNames as $systemName => $name) {
            $permission = new Permission();
            $permission->setName($name);
            $permission->setSystemName($systemName);
            $permissionObjects[] = $permission;
        }

        return $permissionObjects;
    }
}
