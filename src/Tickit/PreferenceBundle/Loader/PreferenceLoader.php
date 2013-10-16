<?php

namespace Tickit\PreferenceBundle\Loader;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository;
use Tickit\PreferenceBundle\Entity\Repository\UserPreferenceValueRepository;
use Tickit\PreferenceBundle\Entity\UserPreferenceValue;
use Tickit\UserBundle\Entity\User;

/**
 * Preference loader.
 *
 * Loads preferences into the current session.
 *
 * @package Tickit\PreferenceBundle\Loader
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
     * The user preference value repository
     *
     * @var UserPreferenceValueRepository
     */
    protected $userPreferenceValueRepository;

    /**
     * The preference repository
     *
     * @var PreferenceRepository
     */
    protected $preferenceRepository;

    /**
     * Constructor.
     *
     * @param SessionInterface              $session                       The current Session instance
     * @param UserPreferenceValueRepository $userPreferenceValueRepository The user preference value repository
     * @param PreferenceRepository          $preferenceRepository          The preference repository
     */
    public function __construct(
        SessionInterface $session,
        UserPreferenceValueRepository $userPreferenceValueRepository,
        PreferenceRepository $preferenceRepository
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
