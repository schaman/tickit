<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\Bundle\UserBundle\Form\Type\FilterFormType;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Controller\Helper\CsrfHelper;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Avatar\Adapter\AvatarAdapterInterface;
use Tickit\Bundle\UserBundle\Doctrine\Repository\UserRepository;
use Tickit\Component\Filter\Map\User\UserFilterMapper;
use Tickit\Component\HttpFoundation\Response\RawJsonResponse;
use Tickit\Component\Model\User\User;
use Tickit\Component\Pagination\Resolver\PageResolver;
use Tickit\Component\Pagination\Response\PaginatedJsonResponse;

/**
 * API controller for users.
 *
 * Serves user content and handles user related operations via API actions.
 *
 * @package Tickit\Bundle\UserBundle\Controller
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
     * The form helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * Constructor
     *
     * @param BaseHelper              $baseHelper     The base controller helper
     * @param CsrfHelper              $csrfHelper     The csrf controller helper
     * @param FilterCollectionBuilder $filterBuilder  The filter collection builder
     * @param UserRepository          $userRepository The user manager
     * @param AvatarAdapterInterface  $avatarAdapter  The avatar adapter
     * @param FormHelper              $formHelper     The form controller helper
     */
    public function __construct(
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        FilterCollectionBuilder $filterBuilder,
        UserRepository $userRepository,
        AvatarAdapterInterface $avatarAdapter,
        FormHelper $formHelper
    ) {
        $this->baseHelper     = $baseHelper;
        $this->csrfHelper     = $csrfHelper;
        $this->filterBuilder  = $filterBuilder;
        $this->userRepository = $userRepository;
        $this->avatarAdapter  = $avatarAdapter;
        $this->formHelper = $formHelper;
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

        $data = $this->baseHelper->getSerializer()->serialize($user);

        return new RawJsonResponse($data);
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
        $request = $this->baseHelper->getRequest();
        $form = $this->formHelper->createForm(new FilterFormType(), null);
        $form->submit($request);

        $filters = $this->filterBuilder->buildFromArray($form->getData(), new UserFilterMapper());
        $users = $this->userRepository->findByFilters($filters, $page);
        $decorator = $this->baseHelper->getObjectCollectionDecorator();

        $data = $decorator->decorate(
            $users->getIterator(),
            ['id', 'forename', 'surname', 'email', 'username', 'admin', 'lastActivity'],
            ['csrf_token' => $this->csrfHelper->generateCsrfToken(UserController::CSRF_DELETE_INTENTION)->getValue()]
        );

        return new PaginatedJsonResponse($data, $users->count(), PageResolver::ITEMS_PER_PAGE, $page);
    }
}
