<?php

/*
 * Tickit, an open source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use DateTime;
use Tickit\Component\Model\User\User;

/**
 * Activity Listener.
 *
 * Listens for controller requests and updates the current user's activity (if a user is logged in)
 *
 * @package Tickit\Bundle\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ActivityListener
{
    /**
     * The security context
     *
     * @var SecurityContext
     */
    protected $context;

    /**
     * The entity manager
     *
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * Class constructor
     *
     * @param SecurityContext        $context The application SecurityContext instance
     * @param EntityManagerInterface $manager The doctrine registry
     */
    public function __construct(SecurityContext $context, EntityManagerInterface $manager)
    {
        $this->context = $context;
        $this->manager = $manager;
    }

    /**
     * Updates the user's last activity time on every request
     *
     * @param FilterControllerEvent $event The controller event
     *
     * @return void
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        //if this isn't the main http request, then we aren't interested...
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $token = $this->context->getToken();
        if ($token instanceof TokenInterface) {
            $user = $token->getUser();
            if ($user instanceof User) {
                $user->setLastActivity(new DateTime());
                $this->manager->persist($user);
                $this->manager->flush();
            }
        }
    }
}
