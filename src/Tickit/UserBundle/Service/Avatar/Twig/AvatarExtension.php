<?php

namespace Tickit\UserBundle\Service\Avatar\Twig;

use Twig_Extension;
use Twig_SimpleFunction;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Avatar twig extension - provides helper functions for templates
 *
 * @package Tickit\UserBundle\Service\Avatar\Twig
 */
class AvatarExtension extends Twig_Extension
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @param ContainerInterface       $container       Service container
     * @param SecurityContextInterface $securityContext Security context to access user object
     */
    public function __construct(ContainerInterface $container, SecurityContextInterface $securityContext)
    {
        $this->container = $container;
        $this->context   = $securityContext;
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
        $avatarAdapter = $this->container->get('avatar')->getAdapter();
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