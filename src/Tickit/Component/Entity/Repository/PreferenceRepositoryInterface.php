<?php

namespace Tickit\Component\Entity\Repository;

/**
 * Preference repository interface.
 *
 * Preference repositories are responsible for providing preference objects
 * from the data layer.
 *
 * @package Tickit\Component\Entity\Repository
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
interface PreferenceRepositoryInterface
{
    /**
     * Finds all preferences and returns them indexed by their system name
     *
     * @param integer[] $exclusions An array of IDs to exclude
     *
     * @return array
     */
    public function findAllWithExclusionsIndexedBySystemName(array $exclusions = array());
}
