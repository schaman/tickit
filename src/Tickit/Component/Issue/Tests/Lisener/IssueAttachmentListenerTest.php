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

namespace Tickit\Component\Issue\Tests\Listener;

use Tickit\Component\Entity\Manager\IssueAttachmentManager;
use Tickit\Component\Event\Issue\AttachmentUploadEvent;
use Tickit\Component\Issue\Listener\IssueAttachmentListener;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * IssueAttachmentListenerTest
 *
 * @package Tickit\Component\Issue\Tests\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $attachmentManager;

    /**
     *
     */
    protected function setUp()
    {
        $this->attachmentManager = $this->getMockBuilder('\Tickit\Component\Entity\Manager\IssueAttachmentManager')
                                        ->disableOriginalConstructor()
                                        ->getMock();
    }

    /**
     * @dataProvider getUploadAttachmentFixtures
     */
    public function testUploadAttachment($attachments)
    {
        $event = new AttachmentUploadEvent($attachments);
        if (!is_array($attachments)) {
            $attachmentsArray = [$attachments];
        } else {
            $attachmentsArray = $attachments;
        }


        $this->attachmentManager->expects($this->exactly(count($attachmentsArray)))
                                ->method('upload')
                                ->will(
                                    $this->returnCallback(
                                        function(IssueAttachment $attachment) {
                                            // let's set the ID so we can be sure the attachment has
                                            // been passed to upload and returned correctly
                                            return $attachment->setId(99);
                                        }
                                    )
                                );

        $i = 0;
        foreach ($attachmentsArray as $attachment) {
            $this->attachmentManager->expects($this->at($i++))
                                    ->method('upload')
                                    ->with($attachment);
        }

        $this->getListener()->uploadAttachments($event);

        foreach ($event->getAttachments() as $uploadedAttachment) {
            $this->assertEquals(99, $uploadedAttachment->getId());
        }
    }

    /**
     * @return array
     */
    public function getUploadAttachmentFixtures()
    {
        $attachment1 = new IssueAttachment();
        $attachment1->setFilename('file1');
        $attachment2 = new IssueAttachment();
        $attachment2->setFilename('file2');
        $attachment3 = new IssueAttachment();
        $attachment3->setFilename('file3');

        return [
            [$attachment1],
            [[$attachment2, $attachment3]],
            [[$attachment1, $attachment2, $attachment3]]
        ];
    }

    /**
     * @return IssueAttachmentListener
     */
    private function getListener()
    {
        return new IssueAttachmentListener($this->attachmentManager);
    }
}
