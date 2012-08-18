<?php

namespace Tickit\PermissionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tickit\CoreBundle\Controller\CoreController;

/**
 * Controller that provides actions for managing group permissions
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class GroupPermissionController extends CoreController
{
    /**
     * Lists group permissions
     *
     * @Template("TickitPermissionBundle:GroupPermission:index.html.twig")
     * @return array
     */
    public function indexAction()
    {
        return array();
    }

}
