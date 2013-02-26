<?php

namespace Tickit\UserBundle\Service\Avatar;

use Symfony\Component\HttpFoundation\Request;
use Tickit\UserBundle\Entity;
use Tickit\UserBundle\Service\Avatar\Adapter\AvatarAdapterInterface;

/**
 * Provides access to avatars based on the current user account
 */
class AvatarService
{
    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface $_securityContext Used to obtain user object
     */
    protected $_securityContext;
    /**
     * @var AvatarAdapterInterface $_adapter Avatar providing adapter
     */
    protected $_adapter;

    /**
     * Service constructor
     *
     * @param Request                $request      Request object
     * @param AvatarAdapterInterface $adapterClass Adapter interface
     */
    public function __construct(Request $request, AvatarAdapterInterface $adapterClass)
    {
        if (!class_exists($adapterClass)) {
            throw new \RuntimeException(sprintf('Adapter class (%s) not found', $adapterClass));
        }

        $this->_adapter = new $adapterClass($request);
    }

    /**
     * Get the provided avatar adapter
     *
     * @return AvatarAdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * Set the avatar adapter
     *
     * @param Adapter\AvatarAdapterInterface $adapter
     */
    public function setAdapter(AvatarAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
    }
}