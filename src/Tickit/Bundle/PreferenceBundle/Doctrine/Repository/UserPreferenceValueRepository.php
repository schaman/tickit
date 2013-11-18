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

namespace Tickit\Bundle\PreferenceBundle\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Entity\Repository\UserPreferenceValueRepositoryInterface;
use Tickit\Component\Model\User\User;

/**
 * Provides a bunch of methods for returning Preference related data from the DBAL
 *
 * @package Tickit\Bundle\PreferenceBundle\Doctrine\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPreferenceValueRepository extends EntityRepository implements UserPreferenceValueRepositoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @param User $user The user to find preferences for
     *
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function findAllForUser(User $user)
    {
        return $this->getFindAllForUserQueryBuilder($user)->getQuery()->execute();
    }

    /**
     * Gets a query builder that finds all preferences for a user
     *
     * @param User $user The user to find preferences for
     *
     * @return QueryBuilder
     */
    public function getFindAllForUserQueryBuilder(User $user)
    {
        $queryBuilder = $this->getEntityManager()
                             ->createQueryBuilder()
                             ->select('upv, p')
                             ->from('TickitPreferenceBundle:UserPreferenceValue', 'upv')
                             ->join('upv.preference', 'p')
                             ->where('upv.user = :user_id')
                             ->setParameter('user_id', $user->getId());

        return $queryBuilder;
    }
}
