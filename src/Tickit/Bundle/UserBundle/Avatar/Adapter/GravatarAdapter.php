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

namespace Tickit\UserBundle\Avatar\Adapter;

use Tickit\UserBundle\Avatar\Entity\AvatarAwareInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gravatar avatar adapter
 *
 * @author Mark Wilson <mark@89allport.co.uk>
 */
class GravatarAdapter implements AvatarAdapterInterface
{
    /**
     * Get the user's gravatar image URL
     *
     * @param AvatarAwareInterface $entity Identifying entity
     * @param int                  $size   Gravatar image size
     *
     * @return string
     */
    public function getImageUrl(AvatarAwareInterface $entity, $size)
    {
        // get the email address associated with the local profile
        $email = $entity->getAvatarIdentifier();

        // build a gravatar url
        $gravatarUrl = 'https://secure.gravatar.com/avatar/';
        $hash = md5(strtolower(trim($email)));
        $queryParams = array(
            // the size of the image
            's' => $size,

            // this is the default image to show
            // check https://en.gravatar.com/site/implement/images/#default-image for more options
            'd' => 'mm'
        );

        return $gravatarUrl . $hash . '?' . http_build_query($queryParams);
    }
}
