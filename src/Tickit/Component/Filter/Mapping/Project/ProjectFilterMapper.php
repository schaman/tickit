<?php

namespace Tickit\Component\Filter\Mapper\Project;

use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Filter\Mapper\FilterMapperInterface;

/**
 * Project Filter Mapper
 *
 * Returns filter mapping information for projects.
 *
 * @package Tickit\Component\Filter\Mapper\Project
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectFilterMapper implements FilterMapperInterface
{
    /**
     * Gets the field map.
     *
     * Returns the mapping of field names to filter types.
     *
     * @return array
     */
    public function getFieldMap()
    {
        return [
            'name' => FilterCollectionBuilder::FILTER_SEARCH,
            'owner' => FilterCollectionBuilder::FILTER_EXACT_MATCH,
            'status' => FilterCollectionBuilder::FILTER_EXACT_MATCH,
            'client' => FilterCollectionBuilder::FILTER_EXACT_MATCH
        ];
    }
}
