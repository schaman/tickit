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

namespace Tickit\Component\Event\Tests\Issue;

use Doctrine\Common\Collections\ArrayCollection;
use Tickit\Component\Event\Issue\AttachmentUploadEvent;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * AttachmentUploadEvent tests
 *
 * @package Tickit\Component\Event\Tests\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class AttachmentUploadEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConstructFixtures
     */
    public function testConstruct($attachments, $expectedAttachments)
    {
        $event = new AttachmentUploadEvent($attachments);

        $this->assertEquals($expectedAttachments, $event->getAttachments());
    }

    /**
     * @return array
     */
    public function getConstructFixtures()
    {
        $attachment = new IssueAttachment();
        $attachment2 = new IssueAttachment();
        $nullAttachment = null;

        return [
            [$attachment, [$attachment]],
            [[$attachment, $attachment2], [$attachment, $attachment2]],
            [new ArrayCollection([$attachment, $attachment2]), [$attachment, $attachment2]],
            [[$nullAttachment, $attachment], [$attachment]],
            [[$nullAttachment], []]
        ];
    }
}
