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

namespace Tickit\Bundle\PreferenceBundle\Tests\Loader;

use Tickit\Bundle\CoreBundle\Tests\AbstractUnitTest;
use Tickit\Bundle\PreferenceBundle\Entity\Preference;
use Tickit\Bundle\PreferenceBundle\Entity\UserPreferenceValue;
use Tickit\Bundle\PreferenceBundle\Loader\PreferenceLoader;
use Tickit\UserBundle\Entity\User;

/**
 * PreferenceLoader tests
 *
 * @package Tickit\Bundle\PreferenceBundle\Tests\Loader
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceLoaderTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $session;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $userPreferenceValueRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $preferenceRepo;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->session = $this->getMockSession();

        $this->userPreferenceValueRepo = $this->getMockBuilder('\Tickit\Bundle\PreferenceBundle\Entity\Repository\UserPreferenceValueRepository')
                                              ->disableOriginalConstructor()
                                              ->getMock();

        $this->preferenceRepo = $this->getMockBuilder('\Tickit\Bundle\PreferenceBundle\Entity\Repository\PreferenceRepository')
                                     ->disableOriginalConstructor()
                                     ->getMock();
    }

    /**
     * Tests the loadForUser() method
     *
     * @param $preferenceValues
     * @param $preferences
     *
     * @dataProvider getPreferenceValues
     * @return void
     */
    public function testLoadForUserLoadsCorrectPreferenceValuesWithSystemDefaultFallbacks(
        $preferenceValues,
        $preferences
    ) {
        $user = new User();

        $this->userPreferenceValueRepo->expects($this->once())
                                      ->method('findAllForUser')
                                      ->with($user)
                                      ->will($this->returnValue($preferenceValues));

        $this->preferenceRepo->expects($this->once())
                             ->method('findAllWithExclusionsIndexedBySystemName')
                             ->with([1, 2])
                             ->will($this->returnValue($preferences));

        $preferenceValue3 = new UserPreferenceValue();
        $preferenceValue3->setPreference($preferences['preference.three'])
                         ->setUser($user)
                         ->setValue('default value 3');

        $preferenceValue4 = new UserPreferenceValue();
        $preferenceValue4->setPreference($preferences['preference.four'])
                         ->setUser($user)
                         ->setValue('default value 4');

        $expectedPreferences = [
            'preference.one' => $preferenceValues[0],
            'preference.two' => $preferenceValues[1],
            'preference.three' => $preferenceValue3,
            'preference.four' => $preferenceValue4
        ];

        $this->session->expects($this->once())
                      ->method('set')
                      ->with(PreferenceLoader::SESSION_PREFERENCES, $expectedPreferences);

        $this->getLoader()->loadForUser($user);
    }

    /**
     * Gets preference value data
     *
     * @return array
     */
    public function getPreferenceValues()
    {
        $preference1 = new Preference();
        $preference1->setId(1);
        $preference1->setSystemName('preference.one');
        $preference2 = new Preference();
        $preference2->setId(2);
        $preference2->setSystemName('preference.two');

        $value1 = new UserPreferenceValue();
        $value1->setPreference($preference1);

        $value2 = new UserPreferenceValue();
        $value2->setPreference($preference2);

        $preference3 = new Preference();
        $preference3->setId(3)
                    ->setSystemName('preference.three')
                    ->setDefaultValue('default value 3');

        $preference4 = new Preference();
        $preference4->setId(4)
                    ->setSystemName('preference.four')
                    ->setDefaultValue('default value 4');

        return [[[$value1, $value2], ['preference.three' => $preference3, 'preference.four' => $preference4]]];
    }

    /**
     * Gets a new loader instance
     *
     * @return PreferenceLoader
     */
    private function getLoader()
    {
        return new PreferenceLoader($this->session, $this->userPreferenceValueRepo, $this->preferenceRepo);
    }
}
