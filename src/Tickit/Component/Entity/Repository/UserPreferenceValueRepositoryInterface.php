<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Entity\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Tickit\Component\Model\User\User;

/**
 * User Preference Value repository interface.
 *
 * User Preference Value repositories are responsible for fetching preference
 * value objects from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface UserPreferenceValueRepositoryInterface extends ObjectRepository
{
    /**
     * Finds preferences for the provided user
     *
     * @param User $user The user to find preferences for
     *
     * @return array
     */
    public function findAllForUser(User $user);
}
