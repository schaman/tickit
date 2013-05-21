<?php

namespace Tickit\PermissionBundle\Tests\Loader;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * PermissionLoader tests
 *
 * @package Tickit\PermissionBundle\Tests\Loader
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class PermissionLoaderTest extends AbstractFunctionalTest
{
    /**
     * Tests the service container configuration
     *
     * @return void
     */
    public function testServiceContainerReturnsCorrectInstance()
    {
        $loader = static::createClient()->getContainer()->get('tickit_permission.loader');

        $this->assertInstanceOf('Tickit\PermissionBundle\Loader\PermissionLoader', $loader);
    }
}
