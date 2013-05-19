<?php

namespace Tickit\PermissionBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Tickit\PermissionBundle\Model\Permission;

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

        $disabled = true;

        if ($data instanceof Permission && $data->isOverridden() !== false) {
            $disabled = false;
        }

        $form->add('userValue', 'checkbox', array('label' => false, 'disabled' => $disabled, 'required' => false));
    }

    /**
     * Pre-bind event.
     *
     * Binds values to underlying permission model
     *
     * @param FormEvent $event The form event
     *
     * @return void
     */
    public function preBind(FormEvent $event)
    {
        $data = $event->getData();
        if (empty($data['overridden'])) {
            return;
        }

        $userValue = (boolean) !empty($data['userValue']);

        $permission = $event->getForm()->getData();
        if ($permission instanceof Permission) {
            $permission->setUserValue($userValue);
        } else {
            $overridden = isset($data['overridden']) ? $data['overridden'] : false;
            $permission = new Permission();
            $permission->setOverridden($overridden);
            $permission->setUserValue($userValue);
            $permission->setName(isset($data['name']) ? $data['name'] : '');
            $permission->setId(isset($data['id']) ? $data['id'] : null);
            $event->getForm()->setData($permission);
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_BIND => 'preBind'
        );
    }
}
