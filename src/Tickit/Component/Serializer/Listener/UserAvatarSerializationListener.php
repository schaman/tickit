<?php

namespace Tickit\Component\Serializer\Listener;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Tickit\Component\Avatar\Adapter\AvatarAdapterInterface;
use Tickit\Component\Model\User\User;

/**
 * UserAvatarSerializationListener
 *
 * @package Tickit\Component\Serializer\Listener
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class UserAvatarSerializationListener
{
    /**
     * An avatar adapter
     *
     * @var AvatarAdapterInterface
     */
    private $avatarAdapter;

    /**
     * Constructor.
     *
     * @param AvatarAdapterInterface $avatarAdapter An avatar adapter
     */
    public function __construct(AvatarAdapterInterface $avatarAdapter)
    {
        $this->avatarAdapter = $avatarAdapter;
    }

    /**
     * Post serialize event handler
     *
     * @param ObjectEvent $event The event object
     */
    public function onPreSerialize(ObjectEvent $event)
    {
        /** @var User $user */
        $user = $event->getObject();
        $avatarIdentifier = $this->avatarAdapter->getImageUrl($user, 35);
        $event->getVisitor()->addData('avatarUrl', $avatarIdentifier);
    }
}
