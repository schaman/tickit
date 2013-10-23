<?php

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
