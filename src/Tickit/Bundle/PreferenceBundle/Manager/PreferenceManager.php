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

namespace Tickit\Bundle\PreferenceBundle\Manager;

use Tickit\Bundle\PreferenceBundle\Entity\Repository\PreferenceRepository;

/**
 * Preference Manager.
 *
 * Provides functionality for managing preference data in the application.
 *
 * @package Tickit\Bundle\PreferenceBundle\Manager
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