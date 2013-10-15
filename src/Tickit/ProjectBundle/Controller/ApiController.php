<?php

namespace Tickit\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;
use Tickit\ProjectBundle\Entity\AbstractAttribute;
use Tickit\ProjectBundle\Entity\Repository\AttributeRepository;
use Tickit\ProjectBundle\Entity\Repository\ProjectRepository;

/**
 * Api project controller.
 *
 * Provides api related actions for projects.
 *
 * @package Tickit\ProjectBundle\Controller
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
     * The project repository
     *
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * Attribute repository
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

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
     * Constructor.
     *
     * @param FilterCollectionBuilder $filterBuilder       The filter collection builder
     * @param ProjectRepository       $projectRepository   The project repository
     * @param AttributeRepository     $attributeRepository The attribute repository
     * @param BaseHelper              $baseHelper          The base controller helper
     * @param CsrfHelper              $csrfHelper          The CSRF controller helper
     */
    public function __construct(
        FilterCollectionBuilder $filterBuilder,
        ProjectRepository $projectRepository,
        AttributeRepository $attributeRepository,
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->projectRepository = $projectRepository;
        $this->attributeRepository = $attributeRepository;
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
    }

    /**
     * Lists all projects in the application
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());

        $projects = $this->projectRepository->findByFilters($filters);

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        $staticProperties = array(
            'csrf_token' => $this->csrfHelper->generateCsrfToken(ProjectController::CSRF_DELETE_INTENTION)
        );
        foreach ($projects as $project) {
            $data[] = $decorator->decorate($project, array('id', 'name', 'created'), $staticProperties);
        }

        return new JsonResponse($data);
    }

    /**
     * Lists all project attributes in the application
     *
     * @return JsonResponse
     */
    public function attributesListAction()
    {
        $filters = $this->filterBuilder->buildFromRequest($this->baseHelper->getRequest());

        $attributes = $this->attributeRepository->findByFilters($filters);

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        /** @var AbstractAttribute $attribute */
        foreach ($attributes as $attribute) {
            $data[] = $decorator->decorate($attribute, array('id', 'type', 'name'));
        }

        return new JsonResponse($data);
    }
}
