<?php

namespace Tickit\PreferenceBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Tickit\PreferenceBundle\Entity\Repository\PreferenceRepository;

/**
 * Preference Manager.
 *
 * Provides functionality for managing preference data in the application.
 *
 * @package Tickit\PreferenceBundle\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PreferenceManager
{
    /**
     * The doctrine registry
     *
     * @var Registry
     */
    protected $doctrine;

    /**
     * Constructor
     *
     * @param Registry $doctrine The doctrine registry
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Gets the preferences repository
     *
     * @return PreferenceRepository
     */
    public function getRepository()
    {
        return $this->doctrine->getRepository('TickitPreferenceBundle:Preference');
    }
}
