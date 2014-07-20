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

namespace Tickit\Component\Entity\Tests\Manager\Issue;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tickit\Component\Entity\Manager\Issue\IssueAttachmentManager;
use Tickit\Component\Model\Issue\IssueAttachment;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueAttachmentManager tests
 *
 * @package Tickit\Component\Entity\Tests\Manager\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentManagerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $filesystem;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $namingStrategy;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->filesystem = $this->getMockBuilder('\Gaufrette\Filesystem')
                                 ->disableOriginalConstructor()
                                 ->getMock();

        $this->em = $this->getMockEntityManager();
        $this->namingStrategy = $this->getMockForAbstractClass('\Tickit\Component\File\Strategy\Naming\NamingStrategyInterface');
    }

    /**
     * Tests the upload() method
     */
    public function testUpload()
    {
        $filePath = __DIR__ . '/assets/test.txt';
        $file = new UploadedFile($filePath, 'file-name.jpeg', 'plain/text', null, null, true);

        $issueAttachment = new IssueAttachment();
        $issueAttachment->setFile($file);

        $this->namingStrategy->expects($this->once())
                             ->method('getName')
                             ->with($file->getClientOriginalName())
                             ->will($this->returnValue('safe-file-name.jpeg'));

        $this->filesystem->expects($this->once())
                         ->method('write')
                         ->with('safe-file-name.jpeg', 'test file');

        $expectedAttachment = clone $issueAttachment;
        $expectedAttachment->setFilename('safe-file-name.jpeg');
        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($expectedAttachment);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->getManager()->upload($issueAttachment);
    }

    /**
     * Gets a new manager instance
     *
     * @return IssueAttachmentManager
     */
    private function getManager()
    {
        return new IssueAttachmentManager($this->filesystem, $this->em, $this->namingStrategy);
    }
}
