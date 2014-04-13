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

use Doctrine\Common\Collections\Collection;
use Symfony\Component\EventDispatcher\Event;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * Attachment Upload event.
 *
 * Event containing newly uploaded attachments.
 *
 * @package Tickit\Component\Event\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttachmentUploadEvent extends Event
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
     * @param IssueAttachment[]|IssueAttachment|Collection $attachments A collection, array or single instance of
     *                                                                  IssueAttachment
     */
    public function __construct($attachments)
    {
        if ($attachments instanceof Collection) {
            $attachments = $attachments->toArray();
        }

        if (!is_array($attachments)) {
            $attachments = array($attachments);
        }

        $this->attachments = $this->sanitize($attachments);
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

    /**
     * Sanitizes an array of attachments.
     *
     * Removes any non-valid attachments.
     *
     * @param array $attachments An array of issue attachments
     *
     * @return array
     */
    private function sanitize(array $attachments)
    {
        $sanitized = [];
        foreach ($attachments as $attachment) {
            if (!$attachment instanceof IssueAttachment) {
                continue;
            }
            $sanitized[] = $attachment;
        }

        return $sanitized;
    }
}
