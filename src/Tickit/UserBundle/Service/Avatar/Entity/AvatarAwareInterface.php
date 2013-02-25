<?php

namespace Tickit\UserBundle\Service\Avatar\Entity;

/**
 * Abstract Avatar aware entity interface
 */
interface AvatarAwareInterface
{
    /**
     * Get the identifier of the user account
     * 
     * @return mixed
     */
    public function getAvatarIdentifier();
}
