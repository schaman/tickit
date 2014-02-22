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
 * The IssueAttachment entity represents a file attachment on a issue
 *
 * @package Tickit\Component\Model\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachment
{
    /**
     * The unique identifier
     *
     * @var integer
     */
    protected $id;

    /**
     * The issue that this attachment belongs to
     *
     * @var Issue
     */
    protected $issue;

    /**
     * The filename of the attachment
     *
     * @var string
     */
    protected $filename;

    /**
     * The mime type of the attachment
     *
     * @var string
     */
    protected $mimeType;

    /**
     * Gets the ID of this comment
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the issue that this comment is attached to
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Sets the issue that this comment is attached to
     *
     * @param Issue $issue
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;
    }

    /**
     * Gets the filename of this attachment
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Sets the filename for this attachment
     *
     * @param string $name
     */
    public function setFilename($name)
    {
        $this->filename = $name;
    }

    /**
     * Gets the mime type for this attachment
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Sets the mime type for this attachment
     *
     * @param string $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }
}
