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

namespace Tickit\Component\Preference\Loader;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tickit\Component\Entity\Repository\UserPreferenceValueRepositoryInterface;
use Tickit\Component\Entity\Repository\PreferenceRepositoryInterface;
use Tickit\Component\Preference\Model\UserPreferenceValue;
use Tickit\Component\Model\User\User;

/**
 * Preference loader.
 *
 * Loads preferences into the current session.
 *
 * @package Tickit\Component\Preference\Loader
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceLoader implements LoaderInterface
{
    const SESSION_PREFERENCES = 'preferences';

    /**
     * The session instance
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * A user preference value repository
     *
     * @var UserPreferenceValueRepositoryInterface
     */
    protected $userPreferenceValueRepository;

    /**
     * A preference repository
     *
     * @var PreferenceRepositoryInterface
     */
    protected $preferenceRepository;

    /**
     * Constructor.
     *
     * @param SessionInterface                       $session                       The current Session instance
     * @param UserPreferenceValueRepositoryInterface $userPreferenceValueRepository The user preference value repository
     * @param PreferenceRepositoryInterface          $preferenceRepository          A preference repository
     */
    public function __construct(
        SessionInterface $session,
        UserPreferenceValueRepositoryInterface $userPreferenceValueRepository,
        PreferenceRepositoryInterface $preferenceRepository
    ) {
        $this->session = $session;
        $this->userPreferenceValueRepository = $userPreferenceValueRepository;
        $this->preferenceRepository = $preferenceRepository;
    }

    /**
     * Loads preference into the current context for the given user.
     *
     * @param User $user The user to load preferences for
     *
     * @return void
     */
    public function loadForUser(User $user)
    {
        $condensedPreferences = array();
        $userPreferences = $this->userPreferenceValueRepository->findAllForUser($user);

        $userPreferenceIds = array_map(
            function (UserPreferenceValue $userPreference) {
                return $userPreference->getPreference()->getId();
            },
            $userPreferences
        );

        // get preferences that the user does not have a value for
        $allPreferences = $this->preferenceRepository->findAllWithExclusionsIndexedBySystemName($userPreferenceIds);

        $mergedPreferences = $userPreferences + $allPreferences;

        array_walk(
            $mergedPreferences,
            function ($item) use ($user, &$condensedPreferences) {
                if ($item instanceof UserPreferenceValue) {
                    $condensedPreferences[$item->getPreference()->getSystemName()] = $item;
                } else {
                    $prefValue = new UserPreferenceValue();
                    $prefValue->setUser($user)
                              ->setPreference($item)
                              ->setValue($item->getDefaultValue());

                    $condensedPreferences[$item->getSystemName()] = $prefValue;
                }
            }
        );

        $this->session->set(static::SESSION_PREFERENCES, $condensedPreferences);
    }
}
