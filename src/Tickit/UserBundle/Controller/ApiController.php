<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\AbstractCoreController;
use Tickit\UserBundle\Entity\User;

/**
 * API controller for users.
 *
 * Serves user content and handles user related operations via API actions.
 *
 * @package Tickit\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ApiController extends AbstractCoreController
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

        $avatarAdapter = $this->container->get('tickit_user.avatar')->getAdapter();
        $avatarUrl     = $avatarAdapter->getImageUrl($user, 35);

        $data = array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'forename' => $user->getForename(),
            'surname' => $user->getSurname(),
            'avatarUrl' => $avatarUrl
        );

        return new JsonResponse($data);
    }

    /**
     * Lists users in the application.
     *
     * @param integer $page The page number of the results to display
     *
     * @return JsonResponse
     */
    public function listAction($page = 1)
    {
        $filters = $this->get('tickit.filter_collection_builder')
                        ->buildFromRequest($this->getRequest());

        $users = $this->get('tickit_user.manager')
                      ->getRepository()
                      ->findByFilters($filters);

        $data = array();
        $decorator = $this->getArrayDecorator();
        foreach ($users as $user) {
            $data[] = $decorator->decorate(
                $user,
                array('id', 'forename', 'surname', 'email', 'username', 'lastActivity')
            );
        }

        return new JsonResponse($data);
    }
}
