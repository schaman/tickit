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

namespace Tickit\Bundle\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\Bundle\ClientBundle\Doctrine\Repository\ClientRepository;
use Tickit\Bundle\ClientBundle\Form\Type\FilterFormType;
use Tickit\Component\Controller\Helper\BaseHelper;
use Tickit\Component\Controller\Helper\CsrfHelper;
use Tickit\Component\Controller\Helper\FormHelper;
use Tickit\Component\Filter\Collection\Builder\FilterCollectionBuilder;
use Tickit\Component\Filter\Map\Client\ClientFilterMapper;
use Tickit\Component\HttpFoundation\Response\RawJsonResponse;
use Tickit\Component\Pagination\PageData;
use Tickit\Component\Pagination\Resolver\PageResolver;

/**
 * API controller.
 *
 * Provides API actions for serving client data to the application.
 *
 * @package Tickit\Bundle\ClientBundle\Controller
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
     * The form helper
     *
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * Constructor.
     *
     * @param FilterCollectionBuilder $filterBuilder    The filter collection builder
     * @param BaseHelper              $baseHelper       The base controller helper
     * @param CsrfHelper              $csrfHelper       The CSRF controller helper
     * @param ClientRepository        $clientRepository The client repository
     * @param FormHelper              $formHelper       The form controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        ClientRepository $clientRepository,
        FormHelper $formHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
        $this->clientRepository = $clientRepository;
        $this->formHelper = $formHelper;
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
        $request = $this->baseHelper->getRequest();
        $form = $this->formHelper->createForm(new FilterFormType(), null);
        $form->submit($request);

        $filters = $this->filterBuilder->buildFromArray($form->getData(), new ClientFilterMapper());
        $clients = $this->clientRepository->findByFilters($filters, $page);

        $data = PageData::create($clients, $clients->count(), PageResolver::ITEMS_PER_PAGE, $page);

        return new RawJsonResponse($this->baseHelper->getSerializer()->serialize($data));
    }
}
