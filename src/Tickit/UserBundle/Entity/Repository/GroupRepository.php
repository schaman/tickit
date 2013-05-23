<?php

namespace Tickit\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Group repository.
 *
 * Provides methods for fetching group related data from the entity manager.
 *
 * @package Tickit\UserBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupRepository extends EntityRepository
{
    /**
     * Finds groups that match the provided filters.
     *
     * @param array $filters An array of filters to search on
     *
     * @return mixed
     */
    public function findGroups(array $filters = array())
    {
        $groupsQ = $this->getEntityManager()
                        ->createQueryBuilder()
                        ->select('g')
                        ->from('TickitUserBundle:Group', 'g');

        foreach ($filters as $column => $value) {
            if (is_string($value)) {
                $groupsQ->where(sprintf('%s LIKE :%s', $column, $column));
                $groupsQ->setParameter($column, $value);
            }
        }

        return $groupsQ->getQuery()->execute();
    }
}
