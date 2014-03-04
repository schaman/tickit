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

namespace Tickit\Bundle\IssueBundle\Tests\Form\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tickit\Bundle\IssueBundle\Form\EventListener\IssueAttachmentMimeTypeFormSubscriber;
use Tickit\Component\Model\Issue\IssueAttachment;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueAttachmentMimeTypeFormSubscriber tests
 *
 * @package Tickit\Bundle\IssueBundle\Tests\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentMimeTypeFormSubscriberTest extends AbstractUnitTest
{
    /**
     * Tests the setMimeType() method
     *
     * @dataProvider getMimeTypeFixtures
     */
    public function testSetMimeType($filePath, $expectedMimeType)
    {
        $rawData = [
            'file' => new UploadedFile($filePath, 'test')
        ];

        $event = $this->getMockFormEvent();

        $event->expects($this->any())
             ->method('getData')
             ->will($this->returnValue($rawData));

        $expectedData = $rawData;
        $expectedData['mimeType'] = $expectedMimeType;

        $event->expects($this->once())
              ->method('setData')
              ->with($expectedData);

        $subscriber = new IssueAttachmentMimeTypeFormSubscriber();
        $subscriber->setMimeType($event);
    }

    /**
     * @return array
     */
    public function getMimeTypeFixtures()
    {
        return [
            [__DIR__ . '/fixtures/test', 'text/plain'],
            [__DIR__ . '/fixtures/test.png', 'image/png'],
            [__DIR__ . '/fixtures/test.jpg', 'image/jpeg']
        ];
    }
}
