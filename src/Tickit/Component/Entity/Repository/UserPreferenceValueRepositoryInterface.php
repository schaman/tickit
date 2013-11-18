<?php

namespace Tickit\Component\Entity\Repository;

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
interface UserPreferenceValueRepositoryInterface
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
