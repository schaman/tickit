<?php

/*
 * Tickit, an open source web based bug management tool.
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

namespace Tickit\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\ClientBundle\Entity\Repository\ClientRepository;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;

/**
 * API controller.
 *
 * Provides API actions for serving client data to the application.
 *
 * @package Tickit\ClientBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class ApiController
{
    /**
     * The filter collection builder
     *
     * @var FilterCollectionBuilder
     */
    protected $filterBuilder;

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
     * The client repository
     *
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * Constructor.
     *
     * @param FilterCollectionBuilder $filterBuilder    The filter collection builder
     * @param BaseHelper              $baseHelper       The base controller helper
     * @param CsrfHelper              $csrfHelper       The CSRF controller helper
     * @param ClientRepository        $clientRepository The client repository
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        ClientRepository $clientRepository
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
        $this->clientRepository = $clientRepository;
    }

    /**
     * Lists all clients in the application
     *
     * @param integer $page The page number of results to return
     *
     * @return JsonResponse
     */
    public function listAction($page = 1)
    {
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());
        $clients = $this->clientRepository->findByFilters($filters);

        $decorator = $this->baseHelper->getObjectCollectionDecorator();
        $staticProperties = [
            'csrf_token' => $this->csrfHelper->generateCsrfToken(ClientController::CSRF_DELETE_INTENTION)
        ];

        $data = $decorator->decorate(
            $clients,
            ['id', 'name', 'url', 'status', 'totalProjects', 'created'],
            $staticProperties)
        ;

        return new JsonResponse($data);
    }
}
