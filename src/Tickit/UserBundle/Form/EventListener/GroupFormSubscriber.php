<?php

namespace Tickit\UserBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Tickit\UserBundle\Entity\Group;

/**
 * File description
 *
 * @package Namespace\Class
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class GroupFormSubscriber implements EventSubscriberInterface
{
    /**
     * Event fired before the form is submitted.
     *
     * Customises the process of binding form data to the underlying entity model
     *
     * @param FormEvent $event The form event object
     *
     * @return void
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $group = $event->getForm()->getData();

        if ($group instanceof Group) {
            $group->setName($data['name']);
        } else {
            $group = new Group($data['name']);
        }

        $event->getForm()->setData($group);
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
