<?php

namespace Tickit\UserBundle\Service\Avatar\Twig;

use Twig_Extension,
    Twig_SimpleFunction;

class AvatarExtension extends Twig_Extension
{
    private $container;

    public function __construct($container, $securityContext)
    {
        $this->container = $container;
        $this->context   = $securityContext;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('my_avatar_url', array($this, 'getCurrentUserAvatarImageUrl'))
        );
    }

    public function getCurrentUserAvatarImageUrl($size)
    {
        $avatarAdapter = $this->container->get('avatar')->getAdapter();
        $user          = $this->context->getToken()->getUser();

        return $avatarAdapter->getImageUrl($user, $size);
    }

    public function getName()
    {
        return 'tickit_user.avatar';
    }
}