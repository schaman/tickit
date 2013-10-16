<?php

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

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        $staticProperties = array(
            'csrf_token' => $this->csrfHelper->generateCsrfToken('client-token')
        );
        foreach ($clients as $client) {
            $data[] = $decorator->decorate($client, array('id', 'name', 'url', 'created'), $staticProperties);
        }

        return new JsonResponse($data);
    }
}
