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

namespace Tickit\Component\Serializer\Listener;

use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GenericSerializationVisitor;
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
    public function onPostSerialize(ObjectEvent $event)
    {
        $visitor = $event->getVisitor();
        if ($visitor instanceof GenericSerializationVisitor) {
            /** @var User $user */
            $user = $event->getObject();
            $avatarIdentifier = $this->avatarAdapter->getImageUrl($user, 35);
            $visitor->addData('avatarUrl', $avatarIdentifier);
        }
    }
}
