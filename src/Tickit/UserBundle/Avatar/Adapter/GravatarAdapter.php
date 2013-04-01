<?php

namespace Tickit\UserBundle\Avatar\Adapter;

use Tickit\UserBundle\Avatar\Entity\AvatarAwareInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Gravatar avatar adapter
 *
 * @author Mark Wilson <mark@enasni.co.uk>
 */
class GravatarAdapter implements AvatarAdapterInterface
{
    /**
     * The request object
     *
     * @var Request
     */
    protected $request;

    /**
     * Initialise the Gravatar avatar adapter
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the user's gravatar image URL
     *
     * @param AvatarAwareInterface $entity Identifying entity
     * @param int                  $size   Gravatar image size
     *
     * @return string
     */
    public function getImageUrl(AvatarAwareInterface $entity, $size)
    {
        $secure = $this->request->isSecure();

        // detect if the image needs to use a secure connection
        if ($secure) {
            $protocol = 'https';
            $subDomain = 'secure';
        } else {
            $protocol = 'http';
            $subDomain = 'www';
        }

        // get the email address associated with the local profile
        $email = $entity->getAvatarIdentifier();

        // build a gravatar url
        $gravatarUrl = $protocol . '://' . $subDomain . '.gravatar.com/avatar/';
        $hash = md5(strtolower(trim($email)));
        $queryParams = array(
            // the size of the image
            's' => $size,

            // this is the default image to show
            // check https://en.gravatar.com/site/implement/images/#default-image for more options
            'd' => 'mm'
        );

        return $gravatarUrl . $hash . '?' . http_build_query($queryParams);
    }
}
