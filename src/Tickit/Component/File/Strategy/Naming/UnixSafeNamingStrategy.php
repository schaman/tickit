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

namespace Tickit\Component\File\Strategy\Naming;

/**
 * Safe naming strategy which uses underscores as replacement.
 *
 * Any illegal characters in the original file name will be replaced
 * with underscores to ensure that the file name is safe to use.
 *
 * @package Tickit\Component\File\Strategy\Naming
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UnixSafeNamingStrategy implements NamingStrategyInterface
{
    /**
     * Gets the name of the file using the strategy.
     *
     * @param string $originalName The original name of the file
     *
     * @return string
     */
    public function getName($originalName)
    {
        return preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $originalName);
    }
}
