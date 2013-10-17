<?php

namespace Tickit\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tickit\CoreBundle\Controller\Helper\BaseHelper;
use Tickit\CoreBundle\Controller\Helper\CsrfHelper;
use Tickit\CoreBundle\Filters\Collection\Builder\FilterCollectionBuilder;
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
     * The avatar service
     *
     * @var AvatarService
     */
    protected $avatar;

    /**
     * Constructor
     *
     * @param BaseHelper              $baseHelper     The base controller helper
     * @param CsrfHelper              $csrfHelper     The csrf controller helper
     * @param FilterCollectionBuilder $filterBuilder  The filter collection builder
     * @param UserRepository          $userRepository The user manager
     * @param AvatarService           $avatar         The avatar service
     */
    public function __construct(
        BaseHelper $baseHelper,
        CsrfHelper $csrfHelper,
        FilterCollectionBuilder $filterBuilder,
        UserRepository $userRepository,
        AvatarService $avatar
    ) {
        $this->baseHelper = $baseHelper;
        $this->csrfHelper = $csrfHelper;
        $this->filterBuilder = $filterBuilder;
        $this->userRepository = $userRepository;
        $this->avatar = $avatar;

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

        $avatarAdapter = $this->avatar->getAdapter();
        $avatarUrl     = $avatarAdapter->getImageUrl($user, 35);

        $data = $this->baseHelper->getObjectDecorator()
                                 ->decorate(
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

        $data = array();
        $decorator = $this->baseHelper->getObjectDecorator();
        $staticProperties = array(
            'csrf_token' => $this->csrfHelper->generateCsrfToken(UserController::CSRF_DELETE_INTENTION)
        );
        foreach ($users as $user) {
            $data[] = $decorator->decorate(
                $user,
                array('id', 'forename', 'surname', 'email', 'username', 'lastActivity'),
                $staticProperties
            );
        }

        return new JsonResponse($data);
    }
}
