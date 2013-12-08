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

namespace Tickit\Component\Filter\Map\User;

use Doctrine\ORM\QueryBuilder;
use Tickit\Component\Filter\AbstractFilter;
use Tickit\Component\Filter\Map\Definition\FilterDefinition;
use Tickit\Component\Filter\Map\FilterMapperInterface;

/**
 * User filter mapper.
 *
 * Returns filter mapping information for users.
 *
 * @package Tickit\Component\Filter\Map\User
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserFilterMapper implements FilterMapperInterface
{
    /**
     * Gets the field map.
     *
     * Returns the mapping of field names to filter types.
     *
     * @return FilterDefinition[]
     */
    public function getFieldMap()
    {
        return [
            'forename' => new FilterDefinition(AbstractFilter::FILTER_SEARCH),
            'surname' => new FilterDefinition(AbstractFilter::FILTER_SEARCH),
            'username' => new FilterDefinition(AbstractFilter::FILTER_SEARCH),
            'email' => new FilterDefinition(AbstractFilter::FILTER_SEARCH),
            'isAdmin' => new FilterDefinition(
                AbstractFilter::FILTER_CALLBACK,
                ['callback' => [$this, 'applyAdminFilter']]
            ),
            'lastActive' => new FilterDefinition(
                AbstractFilter::FILTER_EXACT_MATCH,
                ['comparator' => AbstractFilter::COMPARATOR_GREATER_THAN_OR_EQUAL_TO]
            )
        ];
    }

    /**
     * Applies the "isAdmin" filter to a query
     *
     * @param QueryBuilder $query The query builder
     * @param mixed        $value The filter value
     *
     * @todo Move this somewhere more sensible
     */
    public function applyAdminFilter(QueryBuilder &$query, $value)
    {
        $aliases = $query->getRootAliases();
        $column = sprintf('%s.roles', $aliases[0]);

        if (true === (boolean) $value) {
            $query->andWhere($query->expr()->like($column, '"ROLE_SUPER_ADMIN"'));
        }
    }
}
