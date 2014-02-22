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

use Doctrine\Common\Collections\Collection;

/**
 * Issue type.
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueType
{
    /**
     * The identifier of the issue type
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the issue type
     *
     * @var string
     */
    protected $name;

    /**
     * Issues that are assigned this issue type.
     *
     * @var Collection
     */
    protected $issues;

    /**
     * Sets the identifier of the issue type
     *
     * @param integer $id The identifier
     *
     * @return IssueType
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the identifier of the issue type
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of the issue type
     *
     * @param string $name The issue type name
     *
     * @return IssueType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the name of the issue type
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
