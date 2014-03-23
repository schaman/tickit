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

namespace Tickit\Component\Event\Issue;

use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * Attachment Upload event.
 *
 * Event containing newly uploaded attachments.
 *
 * @package Tickit\Component\Event\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttachmentUploadEvent
{
    /**
     * An array of IssueAttachment objects uploaded
     *
     * @var IssueAttachment[]
     */
    private $attachments;

    /**
     * Constructor.
     *
     * @param IssueAttachment[]|IssueAttachment $attachments An array or single instance of IssueAttachment
     */
    public function __construct($attachments)
    {
        if (!is_array($attachments)) {
            $attachments = array($attachments);
        }

        $this->attachments = $attachments;
    }

    /**
     * Gets the IssueAttachment objects uploaded
     *
     * @return IssueAttachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}
