<?php

namespace Tickit\UserBundle\Service\Avatar\Adapter;

use Tickit\UserBundle\Service\Avatar\Entity\AvatarAwareInterface;
use Symfony\Component\HttpFoundation\Request;

interface AvatarAdapterInterface
{
    /**
     * Initialise the adapter interface
     * @param Request $request
     */
    public function __construct(Request $request);

    /**
     * Get a public facing URL for a specific user's avatar
     *
     * @param AvatarAwareInterface $entity
     * @param $size
     * @return String
     */
    public function getImageUrl(AvatarAwareInterface $entity, $size);
}