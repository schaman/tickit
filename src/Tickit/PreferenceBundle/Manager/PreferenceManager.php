<?php

namespace Tickit\PreferenceBundle\Manager;

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
     * The preference repository
     *
     * @var PreferenceRepository
     */
    protected $preferenceRepository;

    /**
     * Constructor
     *
     * @param PreferenceRepository $preferenceRepository The preference repository
     */
    public function __construct(PreferenceRepository $preferenceRepository)
    {
        $this->preferenceRepository = $preferenceRepository;
    }

    /**
     * Gets the preferences repository
     *
     * @return PreferenceRepository
     */
    public function getRepository()
    {
        return $this->preferenceRepository;
    }
}
