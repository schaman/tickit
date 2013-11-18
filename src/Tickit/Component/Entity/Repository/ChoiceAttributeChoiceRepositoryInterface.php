<?php

namespace Tickit\Component\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Model\Project\ChoiceAttribute;

/**
 * Choice Attribute Choice repository interface.
 *
 * These repositories are responsible for fetching ChoiceAttributeChoice objects
 * from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Model\Project\ChoiceAttributeChoice
 */
interface ChoiceAttributeChoiceRepositoryInterface
{
    /**
     * Finds choices available for a given attribute.
     *
     * @param ChoiceAttribute $attribute The attribute to find choices for
     *
     * @return array
     */
    public function findByAttribute(ChoiceAttribute $attribute);

    /**
     * Gets a QueryBuilder object that finds all choices for the given attribute.
     *
     * @param ChoiceAttribute $attribute The choice attribute to find choices for
     *
     * @return QueryBuilder
     */
    public function getFindAllForAttributeQueryBuilder(ChoiceAttribute $attribute);
}
 