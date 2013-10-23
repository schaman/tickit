<?php

/*
 * Tickit, an source web based bug management tool.
 * 
 * Copyright (C) 2013  Tickit Project <http://tickit.io>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;
use Tickit\UserBundle\Avatar\Adapter\AvatarAdapterInterface;
use Tickit\UserBundle\Avatar\AvatarService;
use Tickit\UserBundle\Entity\Repository\UserRepository;
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
class ApiController
{
    /**
     * The base controller helper
     *
     * @var BaseHelper
     */
    protected $baseHelper;

    /**
     * The CSRF controller helper
     *
     * @var CsrfHelper
     */
    protected $csrfHelper;

    /**
     * Filter builder
     *
     * @var FilterCollectionBuilder
     */
    protected $filterBuilder;

    /**
     * The user repository
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * The avatar adapter
     *
     * @var AvatarAdapterInterface
     */
    protected $avatarAdapter;

    /**
     * Constructor
     *
     * @param BaseHelper              $baseHelper     The base controller helper
     * @param CsrfHelper              $csrfHelper     The csrf controller helper
     * @param FilterCollectionBuilder $filterBuilder  The filter collection builder
     * @param UserRepository          $userRepository The user manager
     * @param AvatarAdapterInterface  $avatarAdapter  The avatar adapter
     */
    public function __construct(
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        FilterCollectionBuilder $filterBuilder,
        UserRepository $userRepository,
        AvatarAdapterInterface $avatarAdapter
    ) {
        $this->baseHelper     = $baseHelper;
        $this->csrfHelper     = $csrfHelper;
        $this->filterBuilder  = $filterBuilder;
        $this->userRepository = $userRepository;
        $this->avatarAdapter  = $avatarAdapter;

    }

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
            $user = $this->baseHelper->getUser();
        }

        $avatarUrl = $this->avatarAdapter->getImageUrl($user, 35);
        $decorator = $this->baseHelper->getObjectDecorator();

        $data = $decorator->decorate(
            $user,
            ['id', 'username', 'email', 'forename', 'surname'],
            ['avatarUrl' => $avatarUrl]
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
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());
        $users = $this->userRepository->findByFilters($filters);
        $decorator = $this->baseHelper->getObjectCollectionDecorator();

        $staticProperties = [
            'csrf_token' => $this->csrfHelper->generateCsrfToken(UserController::CSRF_DELETE_INTENTION)
        ];

        $data = $decorator->decorate(
            $users,
            ['id', 'forename', 'surname', 'email', 'username', 'lastActivity'],
            $staticProperties
        );

        return new JsonResponse($data);
    }
}
