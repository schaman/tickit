<?php

namespace Tickit\Component\Filter\Mapper;

/**
 * Filter Mapper interface.
 *
 * Filter mappers are responsible for returning a map of field names
 * to the filter types that they should implement.
 *
 * @package Tickit\Component\Filter\Mapper
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder
 */
interface FilterMapperInterface
{
    /**
     * Gets the field map.
     *
     * Returns the mapping of field names to filter types.
     *
     * This is usually in the form of an array, where the keys are
     * the field names and the values are the filter types
     *
     * @return array
     */
    public function getFieldMap();
}
