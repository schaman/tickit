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

namespace Tickit\Component\Issue\DataTransformer;

use Tickit\Component\Model\Issue\IssueNumber;

/**
 * Converts string to IssueNumber value object
 *
 * @package Tickit\Component\Issue\DataTransformer
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class StringToIssueNumberDataTransformer
{
    /**
     * Regular expression of issue number strings
     */
    const ISSUE_NUMBER_REGULAR_EXPRESSION = '/^([A-Z]{2,4})([1-9][0-9]{4,})$/';

    /**
     * Indexes in ISSUE_NUMBER_REGULAR_EXPRESSION for prefix and numeric parts
     */
    const ISSUE_NUMBER_PREFIX_INDEX = 1;
    const ISSUE_NUMBER_NUMERIC_INDEX = 2;

    /**
     * Transforms from string to value object
     *
     * @param string $issueNumber Issue number string
     *
     * @return IssueNumber
     *
     * @throws \DomainException If an invalid issue number is used
     */
    public function transform($issueNumber)
    {
        $match = preg_match(static::ISSUE_NUMBER_REGULAR_EXPRESSION, $issueNumber, $matches);

        if (0 === $match) {
            throw new \DomainException('Invalid issue number string');
        }

        $prefix = $matches[static::ISSUE_NUMBER_PREFIX_INDEX];
        $numeric = $matches[static::ISSUE_NUMBER_NUMERIC_INDEX];

        return new IssueNumber($prefix, $numeric);
    }
}
