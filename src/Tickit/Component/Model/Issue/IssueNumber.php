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

namespace Tickit\Component\Model\Issue;

/**
 * Issue Number.
 *
 * Value object representing an issue number.
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueNumber
{
    /**
     * The prefix part of the issue number
     *
     * @var string
     */
    private $prefix;

    /**
     * The number part of the issue number
     *
     * @var integer
     */
    private $number;

    /**
     * Constructor
     *
     * @param string $prefix  The prefix part of the issue number
     * @param integer $number The number part of the issue number
     */
    public function __construct($prefix, $number)
    {
        $this->prefix = $prefix;
        $this->number = $number;
    }

    /**
     * Gets the issue number prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Gets the number part of the issue number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Gets the fully formatted issue number
     *
     * @return string
     */
    public function getIssueNumber()
    {
        return $this->getPrefix() . $this->number;
    }

    /**
     * Casts the issue number to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getIssueNumber();
    }
}
