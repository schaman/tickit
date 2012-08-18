<?php

namespace Tickit\PermissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\CoreBundle\Controller\CoreController;

/**
 * Controller that provides actions for managing user permissions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class UserPermissionController extends CoreController
{

    /**
     * Lists user permissions
     *
     * @Template("TickitPermissionBundle:UserPermission:index.html.twig")
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

}