<?php

namespace Tickit\UserBundle\Service\Avatar;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tickit\UserBundle\Entity;
use Tickit\UserBundle\Service\Avatar\Adapter\AvatarAdapterInterface;

/**
 * Provides access to avatars based on the current user account
 *
 * @author Mark Wilson <mark@enasni.co.uk>
 */
class AvatarService
{
    /**
     * Used to obtain user object
     *
     * @var SecurityContextInterface $_securityContext
     */
    protected $securityContext;

    /**
     * Avatar providing adapter
     *
     * @var AvatarAdapterInterface
     */
    protected $adapter;

    /**
     * Service constructor
     *
     * @param Request $request      Request object
     * @param string  $adapterClass Adapter interface class name
     *
     * @throws \RuntimeException
     */
    public function __construct(Request $request, $adapterClass)
    {
        if (!class_exists($adapterClass)) {
            throw new \RuntimeException(sprintf('Adapter class (%s) not found', $adapterClass));
        }

        $this->adapter = new $adapterClass($request);
    }

    /**
     * Get the provided avatar adapter
     *
     * @return AvatarAdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set the avatar adapter
     *
     * @param Adapter\AvatarAdapterInterface $adapter
     */
    public function setAdapter(AvatarAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
