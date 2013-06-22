<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\UserBundle\Entity\User;

/**
 * API controller for users.
 *
 * Serves user content and handles user related operations via API actions.
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController extends Controller
{
    /**
     * Fetches data for a particular user and serves as JSON
     *
     * @param User $user The user to fetch data for
     *
     * @ParamConverter("user", class="TickitUserBundle:User")
     *
     * @return JsonResponse
     */
    public function fetchAction(User $user = null)
    {
        if (null === $user) {
            $user = $this->getUser();
        }

        $data = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'forename' => $user->getForename(),
            'surname' => $user->getSurname(),
            'avatarIdentifier' => $user->getAvatarIdentifier()
        );

        return new JsonResponse($data);
    }
}
