<?php

namespace Tickit\PreferenceBundle\Tests\Loader;

use Tickit\CoreBundle\Tests\AbstractFunctionalTest;
use Tickit\PreferenceBundle\Entity\UserPreferenceValue;
use Tickit\PreferenceBundle\Loader\PreferenceLoader;

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
        // create new user without any preferences
        $user = $this->createNewUser(true);

        $container = $this->getAuthenticatedClient($user)->getContainer();
        $doctrine = $container->get('doctrine');

        $allPreferences = $doctrine->getRepository('TickitPreferenceBundle:Preference')->findAllIndexedBySystemName();

        $loader = $container->get('tickit_preference.loader');
        $loader->loadForUser($user);

        $sessionPreferences = $container->get('session')->get(PreferenceLoader::SESSION_PREFERENCES);

        $this->assertCount(count($allPreferences), $sessionPreferences);

        /** @var UserPreferenceValue $pref */
        foreach ($sessionPreferences as $pref) {
            $systemName = $pref->getPreference()->getSystemName();
            $this->assertEquals($allPreferences[$systemName]->getDefaultValue(), $pref->getValue());
        }
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
