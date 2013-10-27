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

namespace Tickit\Bundle\UserBundle\Avatar\Twig;

use Tickit\Bundle\UserBundle\Avatar\Adapter\AvatarAdapterInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Avatar twig extension - provides helper functions for templates
 *
 * @package Tickit\Bundle\UserBundle\Service\Avatar\Twig
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class AvatarExtension extends Twig_Extension
{
    /**
     * The avatar adapter
     *
     * @var AvatarAdapterInterface
     */
    private $avatarAdapter;

    /**
     * Constructor.
     *
     * @param AvatarAdapterInterface   $avatarAdapter   Avatar adapter
     * @param SecurityContextInterface $securityContext Security context to access user object
     */
    public function __construct(AvatarAdapterInterface $avatarAdapter, SecurityContextInterface $securityContext)
    {
        $this->avatarAdapter = $avatarAdapter;
        $this->context       = $securityContext;
    }

    /**
     * Get available functions in extension
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('my_avatar_url', array($this, 'getCurrentUserAvatarImageUrl'))
        );
    }

    /**
     * Build avatar image URL
     *
     * @param int $size Size for avatar image
     *
     * @return string
     */
    public function getCurrentUserAvatarImageUrl($size)
    {
        $avatarAdapter = $this->avatarAdapter;
        $user          = $this->context->getToken()->getUser();

        return $avatarAdapter->getImageUrl($user, $size);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'tickit_user.avatar';
    }
}
