<?php

namespace Tickit\PreferenceBundle\Loader;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    const SESSION_PREFERENCES = 'permissions';

    /**
     * The session instance
     *
     * @var SessionInterface
     */
    protected $session;

    /**
     * The doctrine registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Constructor.
     *
     * @param SessionInterface $session  The current Session instance
     * @param Registry         $doctrine The doctrine registry
     */
    public function __construct(SessionInterface $session, Registry $doctrine)
    {
        $this->session = $session;
        $this->doctrine = $doctrine;
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
        $doctrine = $this->doctrine;
        $userPreferences = $doctrine->getRepository('TickitPreferenceBundle:UserPreferenceValue')
                                    ->findAllForUser($user);

        $userPreferenceIds = array_map(
            function (UserPreferenceValue $userPreference) {
                return $userPreference->getPreference()->getId();
            },
            $userPreferences
        );

        // get preferences that the user does not have a value for
        $allPreferences = $doctrine->getRepository('TickitPreferenceBundle:Preference')
                                   ->findAllWithExclusionsIndexedBySystemName($userPreferenceIds);

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
