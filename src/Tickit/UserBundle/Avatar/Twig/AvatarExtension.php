<?php

namespace Tickit\UserBundle\Avatar\Twig;

use Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Avatar twig extension - provides helper functions for templates
 *
 * @package Tickit\UserBundle\Service\Avatar\Twig
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
