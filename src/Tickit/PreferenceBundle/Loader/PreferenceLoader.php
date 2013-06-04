<?php

namespace Tickit\PreferenceBundle\Loader;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Tickit\PreferenceBundle\Entity\Preference;
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
        $doctrine = $this->doctrine;
        $userPreferences = $doctrine->getRepository('TickitPreferenceBundle:UserPreferenceValue')
                                    ->findAllForUser($user, 'systemName');

        $allPreferences = $doctrine->getRepository('TickitPreferenceBundle:Preference')
                                   ->findAllIndexedBySystemName();

        $condensedPreferences = array();

        /** @var Preference $preference */
        foreach ($allPreferences as $preference) {
            $systemName = $preference->getSystemName();
            if (!empty($userPreferences[$preference->getSystemName()])) {
                $userPreference = $userPreferences[$systemName];
            } else {
                $userPreference = new UserPreferenceValue();
                $userPreference->setUser($user)
                               ->setPreference($preference)
                               ->setValue($preference->getDefaultValue());
            }

            $condensedPreferences[$systemName] = $userPreference;
        }

        $this->session->set(static::SESSION_PREFERENCES, $condensedPreferences);
    }
}
