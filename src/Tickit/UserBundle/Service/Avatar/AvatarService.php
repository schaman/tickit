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
    protected $_securityContext;
    protected $_adapter;

    /**
     *
     */
    public function __construct(Request $request, $adapterClass)
    {
        if (!class_exists($adapterClass)) {
            throw new \RuntimeException(sprintf('Adapter class (%s) not found', $adapterClass));
        }

        $this->_adapter = new $adapterClass($request);

//        if ($adapterClass instanceof AvatarAdapterInterface) {
//            throw new \RuntimeException(sprintf('Adapter class (%s) does not implement AvatarAdapterInterface', $adapterClass));
//        }
    }

    /**
     * @return AvatarAdapterInterface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function setAdapter(AvatarAdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
    }
}