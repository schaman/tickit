<?php

namespace Tickit\PreferenceBundle\Tests\Loader;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;

/**
 * PreferenceLoader tests
 *
 * @package Tickit\PreferenceBundle\Tests\Loader
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceLoaderTest extends AbstractFunctionalTest
{
    /**
     * Tests that the service container returns correct service instance
     *
     * @return void
     */
    public function testServiceContainerReturnsValidInstance()
    {
        $container = static::createClient()->getContainer();
        $loader = $container->get('tickit_preference.loader');

        $this->assertInstanceOf('Tickit\PreferenceBundle\Loader\LoaderInterface', $loader);
    }

    /**
     * Tests the loadForUser() method
     *
     * @return void
     */
    public function testLoadForUserLoadsCorrectPreferenceValuesWithSystemDefaultFallbacks()
    {
        $this->markTestIncomplete();
    }

    /**
     * Tests the loadForUser() method
     *
     * @return void
     */
    public function testLoadForUserLoadsCorrectPreferenceValuesWithUserSpecificValues()
    {
        $this->markTestIncomplete();
    }
}
