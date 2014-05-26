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

namespace Tickit\Bundle\IssueBundle\Form\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tickit\Component\Model\Issue\Comment;


/**
 * Comment "createdBy" Listener.
 *
 * Sets the "createdBy" property on a comment when it is created.
 *
 * @package Tickit\Bundle\IssueBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class CommentCreatedByFormSubscriber implements EventSubscriberInterface
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
     * Returns the events to which this class has subscribed.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'setCreatedBy'];
    }

    /**
     * Sets the created by user on the form object
     *
     * @param FormEvent $event The form event object
     */
    public function setCreatedBy(FormEvent $event)
    {
        $data = $event->getData();
        $formData = $event->getForm()->getData();

        if (empty($data) || $formData instanceof Comment) {
            return;
        }

        $data['createdBy'] = $this->securityContext->getToken()->getUser();

        $event->setData($data);
    }
}
