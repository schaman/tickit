<?php

namespace Tickit\PermissionBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description
 *
 * @package Namespace\Class
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class GroupPermissionFormSubscriber implements EventSubscriberInterface
{
    /**
     * Pre-submit event.
     *
     * Binds values to underlying permission model
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        //todo
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmit');
    }
}