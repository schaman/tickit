<?php

namespace Tickit\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Attribute entity repository.
 *
 * Provides methods for fetching project attributes from the data layer
 *
 * @package Tickit\ProjectBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttributeRepository extends EntityRepository
{
    /**
     * Returns a collection of project attributes that match the given filters
     *
     * @param array $filters An array of filters
     *
     * @return mixed
     */
    public function findByFilters(array $filters = array())
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('a')
                      ->from('TickitProjectBundle:AbstractAttribute', 'a');

        foreach ($filters as $column => $value) {
            if (is_string($value)) {
                $query->where(sprintf('%s LIKE :%s', $column, $column));
                $query->setParameter($column, $value);
            }
        }

        return $query->getQuery()->execute();
    }

    /**
     * Returns a deep collection of all project attributes
     *
     * This method includes all associated meta objects related to the attributes.
     *
     * @return mixed
     */
    public function findAllAttributes()
    {
        //TODO: run 3 separate queries, one for ChoiceAttributes, one for LiteralAttributes, one for EntityAttributes and merge
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('a')
                      ->from('TickitProjectBundle:AbstractAttribute', 'a')
                      ->leftJoin('a.choices', 'a');

        return $query->getQuery()->execute();
    }
}
