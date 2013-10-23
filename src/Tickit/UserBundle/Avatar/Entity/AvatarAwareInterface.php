<?php

namespace Tickit\UserBundle\Avatar\Entity;

/**
 * Abstract Avatar aware entity interface
 *
 * @author Mark Wilson <mark@89allport.co.uk>
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
