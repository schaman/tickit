<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Component\Filter\Map\Definition;

/**
 * Filter Definition.
 *
 * Defines a filter's type and option requirements.
 *
 * @package Tickit\Component\Filter\Map\Definition
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @see     Tickit\Component\Filter\Map\FilterMapperInterface
 */
class FilterDefinition implements FilterDefinitionInterface
{
    /**
     * The filter type
     *
     * @var string
     */
    private $type;

    /**
     * Filter options
     *
     * @var array
     */
    private $options;

    /**
     * Constructor.
     *
     * @param string $type    The filter type
     * @param array  $options An array of filter options
     */
    public function __construct($type, array $options = [])
    {
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * Gets the filter type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets an array of options for the filter
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
