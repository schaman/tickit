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

namespace Tickit\Component\Issue\Listener;

use Tickit\Component\Entity\Manager\Issue\IssueAttachmentManager;
use Tickit\Component\Event\Issue\AttachmentUploadEvent;

/**
 * Listener for issue attachment events.
 *
 * @package Tickit\Component\Issue\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentUploadListener
{
    /**
     * The attachment manager
     *
     * @var IssueAttachmentManager
     */
    private $attachmentManager;

    /**
     * Constructor.
     *
     * @param IssueAttachmentManager $attachmentManager The attachment manager
     */
    public function __construct(IssueAttachmentManager $attachmentManager)
    {
        $this->attachmentManager = $attachmentManager;
    }

    /**
     * Handles the uploading of attachments.
     *
     * Hooks into the "tickit_issue.event.attachment_upload" event.
     *
     * @param AttachmentUploadEvent $event The event object
     */
    public function uploadAttachments(AttachmentUploadEvent $event)
    {
        $attachments = $event->getAttachments();

        foreach ($attachments as &$attachment) {
            $this->attachmentManager->upload($attachment);
        }
    }
}
