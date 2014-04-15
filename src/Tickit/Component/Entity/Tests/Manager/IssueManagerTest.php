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

namespace Tickit\Component\Entity\Tests\Manager;

use Doctrine\ORM\UnitOfWork;
use Tickit\Component\Entity\Event\EntityEvent;
use Tickit\Component\Entity\Manager\IssueManager;
use Tickit\Component\Event\Issue\AttachmentUploadEvent;
use Tickit\Component\Event\Issue\IssueEvents;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Issue\IssueAttachment;
use Tickit\Component\Test\AbstractUnitTest;

/**
 * IssueManager tests
 *
 * @package Tickit\Component\Entity\Tests\Manager
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class IssueManagerTest extends AbstractUnitTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $issueRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $em;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $entityEventDispatcher;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->issueRepository = $this->getMockBuilder('\Tickit\Component\Entity\Repository\IssueRepositoryInterface')
                                      ->disableOriginalConstructor()
                                      ->getMock();

        $this->em = $this->getMockEntityManager();

        $entityEventDispatcherMethods = [
            'dispatchBeforeCreateEvent',
            'dispatchCreateEvent',
            'dispatchBeforeUpdateEvent',
            'dispatchUpdateEvent',
            'getEventNames'
        ];
        $this->entityEventDispatcher = $this->getMockBuilder('\Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher')
                                            ->disableOriginalConstructor()
                                            ->setMethods($entityEventDispatcherMethods)
                                            ->getMock();

        $this->eventDispatcher = $this->getMockEventDispatcher();
    }

    /**
     * Tests the getRepository() method
     */
    public function testGetRepository()
    {
        $this->assertSame($this->issueRepository, $this->getManager()->getRepository());
    }

    /**
     * @dataProvider getCreateIssueFixtures
     */
    public function testCreateIssue($withAttachment, $expectedIssue)
    {
        $this->assertEquals($expectedIssue, $this->getManager()->createIssue($withAttachment));
    }

    /**
     * @return array
     */
    public function getCreateIssueFixtures()
    {
        $attachment = new IssueAttachment();
        $issue = new Issue();
        $issueWithAttachment = new Issue();
        $issueWithAttachment->setAttachments(array($attachment));

        return [
            [true, $issueWithAttachment],
            [false, $issue]
        ];
    }

    /**
     * @dataProvider getCreateIssueWithAttachmentsFixtures
     */
    public function testCreateWithAttachments(Issue $issue, Issue $issueWithoutAttachments)
    {
        $beforeEvent = new EntityEvent($issueWithoutAttachments);

        // if the issue has attachments, we expect the event dispatcher
        // to notify the rest of the application that they have been uploaded
        $uploadEvent = new AttachmentUploadEvent($issue->getAttachments());
        $this->eventDispatcher->expects($this->once())
                              ->method('dispatch')
                              ->with(IssueEvents::ISSUE_ATTACHMENT_UPLOAD, $uploadEvent);

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($issue);

        $this->em->expects($this->exactly(2))
                 ->method('flush');

        $this->trainEntityManagerUnitOfWorkToHandleNewAttachments(2);

        $beforeCreateCallback = function ($issueWithoutAttachments) use ($beforeEvent) {
            $this->assertFalse($issueWithoutAttachments->hasAttachments());

            return $beforeEvent;
        };

        $this->entityEventDispatcher->expects($this->once())
                                    ->method('dispatchBeforeCreateEvent')
                                    ->will($this->returnCallback($beforeCreateCallback));

        $this->trainEntityEventDispatcherToDispatchEvent($issue, 'dispatchCreateEvent');

        $this->assertSame($issue, $this->getManager()->create($issue));
    }

    /**
     * @return array
     */
    public function getCreateIssueWithAttachmentsFixtures()
    {
        $issueWithAttachments = new Issue();
        $attachment1 = new IssueAttachment();
        $attachment2 = new IssueAttachment();

        $issueWithAttachments->addAttachment($attachment1);
        $issueWithAttachments->addAttachment($attachment2);

        return [
            [$issueWithAttachments, new Issue()]
        ];
    }

    /**
     * @dataProvider getCreateFixtures
     */
    public function testCreate(Issue $issue)
    {
        $beforeEvent = new EntityEvent($issue);

        // if there are no attachments on the issue then we don't expect
        // the event dispatcher to be triggered
        $this->eventDispatcher->expects($this->never())
                              ->method('dispatch');

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($issue);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->trainEntityEventDispatcherToDispatchEvent($issue, 'dispatchBeforeCreateEvent', $beforeEvent);
        $this->trainEntityEventDispatcherToDispatchEvent($issue, 'dispatchCreateEvent');

        $this->assertSame($issue, $this->getManager()->create($issue));
    }

    /**
     * @return array
     */
    public function getCreateFixtures()
    {
        $issue = new Issue();

        return [
            [$issue]
        ];
    }

    /**
     * @dataProvider getUpdateIssueWithAttachmentsFixtures
     */
    public function testUpdateWithAttachments(Issue $issue, Issue $issueWithoutAttachments, array $newAttachments)
    {
        $beforeEvent = new EntityEvent($issueWithoutAttachments);

        // if the issue has attachments, we expect the event dispatcher
        // to notify the rest of the application that they have been uploaded,
        // but the attachments dispatched on the upload event should only be
        // the NEW attachments
        $uploadEvent = new AttachmentUploadEvent($newAttachments);
        $this->eventDispatcher->expects($this->once())
                              ->method('dispatch')
                              ->with(IssueEvents::ISSUE_ATTACHMENT_UPLOAD, $uploadEvent);

        $this->em->expects($this->once())
            ->method('persist')
            ->with($issue);

        $this->em->expects($this->exactly(2))
            ->method('flush');

        $this->trainEntityManagerUnitOfWorkToHandleNewAttachments(4);

        $beforeCreateCallback = function ($issueWithoutAttachments) use ($beforeEvent) {
            $this->assertFalse($issueWithoutAttachments->hasAttachments());

            return $beforeEvent;
        };

        $this->entityEventDispatcher->expects($this->once())
                                    ->method('dispatchBeforeUpdateEvent')
                                    ->will($this->returnCallback($beforeCreateCallback));

        $this->trainEntityEventDispatcherToDispatchEvent($issue, 'dispatchUpdateEvent');

        $this->assertSame($issue, $this->getManager()->update($issue));
    }

    /**
     * @return array
     */
    public function getUpdateIssueWithAttachmentsFixtures()
    {
        $issueWithAttachments = new Issue();
        $newAttachment1 = new IssueAttachment();
        $newAttachment2 = new IssueAttachment();
        $existingAttachment1 = new IssueAttachment();
        $existingAttachment1->setId(1);
        $existingAttachment1 = new IssueAttachment();
        $existingAttachment1->setId(2);

        $issueWithAttachments->addAttachment($newAttachment1)
                             ->addAttachment($newAttachment2)
                             ->addAttachment($existingAttachment1)
                             ->addAttachment($existingAttachment1);

        return [
            [$issueWithAttachments, new Issue(), array($newAttachment1, $newAttachment2)],
        ];
    }

    /**
     * @dataProvider getUpdateFixtures
     */
    public function testUpdate(Issue $issue)
    {
        $beforeEvent = new EntityEvent($issue);

        // if there are no attachments on the issue then we don't expect
        // the event dispatcher to be triggered
        $this->eventDispatcher->expects($this->never())
                              ->method('dispatch');

        $this->em->expects($this->once())
                 ->method('persist')
                 ->with($issue);

        $this->em->expects($this->once())
                 ->method('flush');

        $this->trainEntityEventDispatcherToDispatchEvent($issue, 'dispatchBeforeUpdateEvent', $beforeEvent);
        $this->trainEntityEventDispatcherToDispatchEvent($issue, 'dispatchUpdateEvent');

        $this->assertSame($issue, $this->getManager()->update($issue));
    }

    /**
     * @return array
     */
    public function getUpdateFixtures()
    {
        $issue = new Issue();

        return [
            [$issue]
        ];
    }

    /**
     * Gets a new manager instance
     *
     * @return IssueManager
     */
    private function getManager()
    {
        return new IssueManager(
            $this->issueRepository,
            $this->em,
            $this->entityEventDispatcher,
            $this->eventDispatcher
        );
    }

    private function trainEntityEventDispatcherToDispatchEvent(Issue $issue, $methodName, $returnEvent = null)
    {
        $expectation = $this->entityEventDispatcher->expects($this->once())
                                                   ->method($methodName)
                                                   ->with($issue);

        if (null !== $returnEvent) {
            $expectation->will($this->returnValue($returnEvent));
        }

    }

    private function trainEntityManagerUnitOfWorkToHandleNewAttachments($numberOfAttachments)
    {
        $mockUnitOfWork = $this->getMockBuilder('\Doctrine\ORM\UnitOfWork')
                                       ->disableOriginalConstructor()
                                       ->getMock();
        $mockUnitOfWork->expects($this->exactly($numberOfAttachments))
                       ->method('getEntityState')
                       ->will(
                           $this->returnCallback(
                               function(IssueAttachment $attachment) {
                                   return null === $attachment->getId() ? UnitOfWork::STATE_NEW : UnitOfWork::STATE_MANAGED;
                               }
                           )
                       );
        $this->em->expects($this->exactly($numberOfAttachments))
                 ->method('getUnitOfWork')
                 ->will($this->returnValue($mockUnitOfWork));
    }
}
