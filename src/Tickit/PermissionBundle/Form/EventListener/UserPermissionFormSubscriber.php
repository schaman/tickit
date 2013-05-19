<?php

namespace Tickit\PermissionBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Event subscriber for the UserPermissionForm.
 *
 * This form is responsible for determining the state of the user permission form
 * based off its data.
 *
 * @package Tickit\PermissionBundle\Form\EventListener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserPermissionFormSubscriber implements EventSubscriberInterface
{
    /**
     * Pre-set data event, fired before data is bound.
     *
     * @param FormEvent $event The form event object
     *
     * @return void
     */
    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data || null === $data->getUserValue()) {
            $userValueDisabled = true;
        } else {
            $userValueDisabled = false;
        }

        $form->add('userValue', 'checkbox', array('disabled' => $userValueDisabled, 'required' => false));
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }
}