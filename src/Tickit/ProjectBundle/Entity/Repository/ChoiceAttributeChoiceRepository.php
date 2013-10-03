<?php

namespace Tickit\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Tickit\ProjectBundle\Entity\ChoiceAttribute;

/**
 * ChoiceAttributeChoice repository.
 *
 * Provides functionality for fetching data for ChoiceAttributeChoice entities
 *
 * @package Tickit\ProjectBundle\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ChoiceAttributeChoiceRepository extends EntityRepository
{
    /**
     * Gets a QueryBuilder object that finds all choices for the given attribute.
     *
     * @param ChoiceAttribute $attribute The choice attribute to find choices for
     *
     * @return QueryBuilder
     */
    public function getFindAllForAttributeQueryBuilder(ChoiceAttribute $attribute)
    {
        $query = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('c')
                      ->from('TickitProjectBundle:ChoiceAttributeChoice', 'c')
                      ->where('c.attribute = :attribute')
                      ->setParameter('attribute', $attribute->getId());

        return $query;
    }
}
