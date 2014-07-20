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

namespace Tickit\Component\Entity\Manager\Issue;

use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\Filesystem;
use Tickit\Component\File\Strategy\Naming\NamingStrategyInterface;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * Issue Attachment manager.
 *
 * @package Tickit\Component\Entity\Manager\Issue
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueAttachmentManager
{
    /**
     * Filesystem adapter for issue attachments
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * An entity manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * A file naming strategy used for new attachments
     *
     * @var NamingStrategyInterface
     */
    private $fileNamingStrategy;

    /**
     * Constructor.
     *
     * @param Filesystem              $filesystem         A filesystem adapter where issue attachments are stored.
     * @param EntityManagerInterface  $em                 An entity manager
     * @param NamingStrategyInterface $fileNamingStrategy A file naming strategy used for new attachments
     */
    public function __construct(
        Filesystem $filesystem,
        EntityManagerInterface $em,
        NamingStrategyInterface $fileNamingStrategy
    ) {
        $this->filesystem = $filesystem;
        $this->em = $em;
        $this->fileNamingStrategy = $fileNamingStrategy;
    }

    /**
     * Uploads an attachment to the file system.
     *
     * Returns the updated IssueAttachment object.
     *
     * @param IssueAttachment $attachment The uploaded file attachment
     *
     * @return IssueAttachment
     */
    public function upload(IssueAttachment $attachment)
    {
        $file = $attachment->getFile();
        $fileContent = file_get_contents($file->getRealPath());
        $key = $this->fileNamingStrategy->getName($file->getClientOriginalName());

        $this->filesystem->write($key, $fileContent);

        $attachment->setFilename($key);

        $this->em->persist($attachment);
        $this->em->flush();
    }
}
