<?php

namespace Tickit\UserBundle\Listener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use DateTime;
use Tickit\UserBundle\Entity\User;

/**
 * Activity listener.
 *
 * Listens for controller requests and updates the current user's activity (if a user is logged in)
 *
 * @package Tickit\UserBundle\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class Activity
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
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Class constructor
     *
     * @param SecurityContext  $context  The application SecurityContext instance
     * @param Registry         $doctrine The doctrine registry
     */
    public function __construct(SecurityContext $context, Registry $doctrine)
    {
        $this->context = $context;
        $this->manager = $doctrine->getManager();
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
                $this->manager->flush($user);

                //todo: update the user's session here
            }
        }
    }
}
