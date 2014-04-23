<?php

namespace Tickit\Component\Issue\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Tickit\Component\Entity\Event\EntityEvent;

/**
 * Listener for setting "createdBy" property on an Issue
 *
 * @package Tickit\Component\Issue\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class IssueCreatedByListener
{
    /**
     * A security context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext A security context
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Hooks into the issue creation.
     *
     * Sets the "createdBy" property on the Issue to the currently
     * authenticated user.
     *
     * @param EntityEvent $event The event object containing the issue
     */
    public function onIssueCreate(EntityEvent $event)
    {
        $user = $this->securityContext->getToken()->getUser();

        $event->getEntity()->setCreatedBy($user);
    }
}
