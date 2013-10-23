<?php

namespace Tickit\UserBundle\Avatar\Adapter;

use Tickit\UserBundle\Avatar\Entity\AvatarAwareInterface;

/**
 * Avatar adapter interface for implementing standard image accessor functions
 *
 * @package Tickit\UserBundle\Service\Avatar\Adapter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
interface AvatarAdapterInterface
{
    /**
     * Get a public facing URL for a specific user's avatar
     *
     * @param AvatarAwareInterface $entity Identifying object
     * @param int                  $size   Image size
     *
     * @return String
     */
    public function getImageUrl(AvatarAwareInterface $entity, $size);
}
