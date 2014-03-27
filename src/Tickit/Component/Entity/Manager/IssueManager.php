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

namespace Tickit\Component\Entity\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Tickit\Component\Entity\Repository\IssueRepositoryInterface;
use Tickit\Component\Event\Dispatcher\AbstractEntityEventDispatcher;
use Tickit\Component\Model\Issue\Issue;
use Tickit\Component\Model\Issue\IssueAttachment;

/**
 * Issue manager.
 *
 * @package Tickit\Component\Entity\Manager
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueManager extends AbstractManager
{
    /**
     * An issue repository.
     *
     * @var IssueRepositoryInterface
     */
    private $issueRepository;

    /**
     * Constructor.
     *
     * @param IssueRepositoryInterface      $issueRepository An issue repository
     * @param EntityManagerInterface        $em              An entity manager
     * @param AbstractEntityEventDispatcher $dispatcher      An entity event dispatcher
     */
    public function __construct(
        IssueRepositoryInterface $issueRepository,
        EntityManagerInterface $em,
        AbstractEntityEventDispatcher $dispatcher
    ) {
        $this->issueRepository = $issueRepository;

        parent::__construct($em, $dispatcher);
    }

    /**
     * Gets the entity repository.
     *
     * This method returns the entity repository that is associated with this manager's entity.
     *
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->issueRepository;
    }

    /**
     * Creates a new instance of an issue
     *
     * @param boolean $withDummyAttachment True to also create an attachment associated with the issue.
     *
     * @return Issue
     */
    public function createIssue($withDummyAttachment)
    {
        $issue = new Issue();
        if (true === $withDummyAttachment) {
            $issue->setAttachments([new IssueAttachment()]);
        }

        return $issue;
    }

    /**
     * Returns the original entity from the entity manager.
     *
     * This method takes an entity and returns a copy in its original state
     * from the entity manager. This is used when dispatching entity update
     * events, so a before and after comparison can take place.
     *
     * @param object $entity The entity in its current state
     *
     * @return Issue
     */
    protected function fetchEntityInOriginalState($entity)
    {
        return $this->issueRepository->find($entity->getId());
    }
}
